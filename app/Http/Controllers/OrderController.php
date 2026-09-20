<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Notifications\OrderPaidNotification;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display order details, receipt, and live fulfillment timeline.
     */
    public function show(
        string $order_number,
        Request $request,
        WhatsAppNotificationService $whatsAppService
    ): View {
        $order = Order::with(['items.menuItem', 'deliveryZone', 'payments'])
            ->where('order_number', $order_number)
            ->firstOrFail();

        // Handle simulated gateway callback in dev/testing
        if ($request->has('simulated_gateway') && $order->status === 'pending_payment') {
            $order->update(['status' => 'paid']);

            Payment::where('order_id', $order->id)->update([
                'status' => 'successful',
                'paid_at' => now(),
            ]);

            try {
                if ($order->user) {
                    $order->user->notify(new OrderPaidNotification($order));
                } else {
                    Notification::route('mail', $order->customer_email)
                        ->notify(new OrderPaidNotification($order));
                }
                $whatsAppService->sendOrderUpdate($order, "Payment confirmed for order #{$order->order_number}! Our kitchen has started preparing your order.");
            } catch (\Exception $e) {
                // Ignore notification error during simulation
            }
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Order tracking lookup page.
     */
    public function track(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'order_number' => ['required', 'string'],
                'customer_email' => ['required', 'email'],
            ]);

            $order = Order::where('order_number', trim($validated['order_number']))
                ->where('customer_email', trim($validated['customer_email']))
                ->first();

            if ($order) {
                return redirect()->route('orders.show', $order->order_number);
            }

            return back()->withInput()->with('error', 'No order found matching this order number and email address.');
        }

        return view('orders.track');
    }

    /**
     * Display bank transfer payment instructions.
     */
    public function bankTransfer(string $order_number): View
    {
        $order = Order::with(['items', 'deliveryZone'])
            ->where('order_number', $order_number)
            ->firstOrFail();

        return view('orders.bank-transfer', compact('order'));
    }
}
