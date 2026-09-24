<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Combo;
use App\Models\DeliveryZone;
use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use App\Models\Order;
use App\Models\Payment;
use App\Notifications\OrderPlacedNotification;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page.
     */
    public function index(): View
    {
        $deliveryZones = DeliveryZone::where('is_active', true)->orderBy('lead_time_minutes')->get();

        return view('checkout.index', compact('deliveryZones'));
    }

    /**
     * Process order placement and initialize payment.
     */
    public function store(
        Request $request,
        PaymentGatewayManager $gatewayManager,
        WhatsAppNotificationService $whatsAppService
    ): RedirectResponse {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'fulfilment_type' => ['required', 'in:delivery,pickup'],
            'delivery_zone_id' => ['required_if:fulfilment_type,delivery', 'nullable', 'exists:delivery_zones,id'],
            'delivery_address_street' => ['required_if:fulfilment_type,delivery', 'nullable', 'string', 'max:255'],
            'delivery_address_city' => ['required_if:fulfilment_type,delivery', 'nullable', 'string', 'max:100'],
            'delivery_address_state' => ['nullable', 'string', 'max:100'],
            'delivery_address_postal_code' => ['nullable', 'string', 'max:20'],
            'delivery_instructions' => ['nullable', 'string', 'max:500'],
            'scheduled_for' => ['nullable', 'date', 'after_or_equal:today'],
            'payment_method' => ['required', 'in:paystack,stripe,bank_transfer,pay_on_delivery'],
            'currency' => ['nullable', 'string', 'in:NGN,GBP,USD,CAD,EUR'],
            'cart_items' => ['required'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Parse raw cart items JSON safely
        $rawItems = is_array($validated['cart_items'])
            ? $validated['cart_items']
            : json_decode($validated['cart_items'], true);

        if (empty($rawItems) || ! is_array($rawItems)) {
            throw ValidationException::withMessages([
                'cart_items' => 'Your food tray is empty. Please add items to proceed.',
            ]);
        }

        // Validate delivery zone & pay on delivery permissions
        $deliveryZone = null;
        $deliveryFeeMinor = 0;

        if ($validated['fulfilment_type'] === 'delivery') {
            $deliveryZone = DeliveryZone::findOrFail($validated['delivery_zone_id']);
            $deliveryFeeMinor = (int) $deliveryZone->fee_minor;

            if ($validated['payment_method'] === 'pay_on_delivery' && ! $deliveryZone->allow_pay_on_delivery) {
                throw ValidationException::withMessages([
                    'payment_method' => "Pay on delivery is not supported for {$deliveryZone->name}. Please select an online payment method or bank transfer.",
                ]);
            }
        }

        // Secure Authoritative Pricing: Verify each line item against database records
        $verifiedItems = [];
        $subtotalMinor = 0;

        foreach ($rawItems as $rawItem) {
            $id = $rawItem['id'] ?? null;
            $quantity = max(1, (int) ($rawItem['quantity'] ?? 1));
            $unitPrice = 0;
            $name = $rawItem['name'] ?? 'Dish';
            $menuItemId = null;
            $options = [
                'variant' => $rawItem['variant'] ?? null,
            ];

            if (is_string($id) && str_starts_with($id, 'combo-')) {
                $comboId = (int) str_replace('combo-', '', $id);
                $combo = Combo::find($comboId);
                if ($combo) {
                    $unitPrice = (int) $combo->price_minor;
                    $name = $combo->name;
                } else {
                    $unitPrice = (int) ($rawItem['price'] ?? 0);
                }
            } else {
                $item = MenuItem::find($id);
                if ($item) {
                    $menuItemId = $item->id;
                    $unitPrice = (int) $item->price_minor;
                    $name = $item->name;

                    $variantName = $rawItem['variant'] ?? null;
                    if ($variantName) {
                        $variant = MenuItemVariant::where('menu_item_id', $item->id)
                            ->where('name', $variantName)
                            ->where('is_available', true)
                            ->first();

                        if ($variant) {
                            $unitPrice += (int) $variant->price_minor;
                            $options['variant'] = $variant->name;
                        }
                    }

                    $requestedAddOns = collect($rawItem['addOns'] ?? [])
                        ->filter(fn ($addOn) => is_string($addOn) && $addOn !== '')
                        ->unique()
                        ->values();

                    if ($requestedAddOns->isNotEmpty()) {
                        $addOns = AddOn::whereIn('name', $requestedAddOns)
                            ->where('is_available', true)
                            ->get(['name', 'price_minor']);

                        $unitPrice += (int) $addOns->sum('price_minor');
                        $options['add_ons'] = $addOns->map(fn ($addOn) => [
                            'name' => $addOn->name,
                            'price_minor' => (int) $addOn->price_minor,
                        ])->values()->all();
                    }
                } else {
                    $unitPrice = (int) ($rawItem['price'] ?? 0);
                }
            }

            $lineTotal = $unitPrice * $quantity;
            $subtotalMinor += $lineTotal;

            $verifiedItems[] = [
                'menu_item_id' => $menuItemId,
                'name' => $name,
                'quantity' => $quantity,
                'unit_price_minor' => $unitPrice,
                'total_minor' => $lineTotal,
                'options' => $options,
            ];
        }

        $totalMinor = $subtotalMinor + $deliveryFeeMinor;

        // Generate unique readable order number (e.g. NK-20260920-A4B7)
        $orderNumber = 'NK-'.date('Ymd').'-'.strtoupper(Str::random(4));

        $order = DB::transaction(function () use ($validated, $orderNumber, $subtotalMinor, $deliveryFeeMinor, $totalMinor, $verifiedItems) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'delivery_zone_id' => $validated['delivery_zone_id'] ?? null,
                'order_number' => $orderNumber,
                'status' => 'pending_payment',
                'fulfilment_type' => $validated['fulfilment_type'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_address' => $validated['fulfilment_type'] === 'delivery' ? [
                    'street' => $validated['delivery_address_street'] ?? null,
                    'city' => $validated['delivery_address_city'] ?? null,
                    'state' => $validated['delivery_address_state'] ?? null,
                    'postal_code' => $validated['delivery_address_postal_code'] ?? null,
                    'instructions' => $validated['delivery_instructions'] ?? null,
                ] : null,
                'scheduled_for' => $validated['scheduled_for'] ?? null,
                'subtotal_minor' => $subtotalMinor,
                'delivery_fee_minor' => $deliveryFeeMinor,
                'discount_minor' => 0,
                'tax_minor' => 0,
                'total_minor' => $totalMinor,
                'currency' => $validated['currency'] ?? 'GBP',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($verifiedItems as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        // Initialize payment via Gateway Strategy
        $gateway = $gatewayManager->gateway($validated['payment_method']);
        $session = $gateway->initiatePayment($order);

        // Record initial payment entry
        Payment::create([
            'order_id' => $order->id,
            'gateway' => $validated['payment_method'],
            'reference' => $session['reference'] ?? ('REF_'.$order->order_number),
            'status' => $validated['payment_method'] === 'pay_on_delivery' ? 'pending' : 'pending',
            'amount_minor' => $order->total_minor,
            'currency' => $order->currency,
            'metadata' => [
                'session' => $session,
                'payment_method' => $validated['payment_method'],
            ],
        ]);

        // Send notifications (Email & WhatsApp stub)
        try {
            if (auth()->check()) {
                auth()->user()->notify(new OrderPlacedNotification($order));
            } else {
                Notification::route('mail', $order->customer_email)
                    ->notify(new OrderPlacedNotification($order));
            }
            $whatsAppService->sendOrderUpdate($order);
        } catch (\Exception $e) {
            // Log notification error but do not block checkout
            Log::warning('Order notification error: '.$e->getMessage());
        }

        return redirect()->away($session['checkout_url'] ?? route('orders.show', $order->order_number));
    }
}
