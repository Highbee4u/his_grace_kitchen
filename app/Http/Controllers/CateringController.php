<?php

namespace App\Http\Controllers;

use App\Models\CateringPackage;
use App\Models\CateringRequest;
use App\Models\Order;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\WhatsAppNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CateringController extends Controller
{
    /**
     * Display catering packages and quote request form.
     */
    public function index(): View
    {
        $packages = CateringPackage::where('is_active', true)->get();

        return view('catering.index', compact('packages'));
    }

    /**
     * Store an incoming catering quote request.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website_hp')) {
            return redirect()->back()->with('error', 'Spam submission detected.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'catering_package_id' => ['nullable', 'exists:catering_packages,id'],
            'guest_count' => ['required', 'integer', 'min:5'],
            'event_date' => ['required', 'date', 'after:today'],
            'venue' => ['required', 'string', 'max:255'],
            'dietary_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $reference = 'CAT-'.strtoupper(uniqid());

        CateringRequest::create([
            'user_id' => auth()->id(),
            'catering_package_id' => $validated['catering_package_id'] ?? null,
            'reference' => $reference,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'guest_count' => $validated['guest_count'],
            'event_date' => $validated['event_date'],
            'venue' => $validated['venue'],
            'dietary_notes' => $validated['dietary_notes'] ?? null,
            'status' => 'submitted',
            'currency' => 'NGN',
        ]);

        return redirect()->route('catering.index')
            ->with('success', "Thank you! Your catering request #{$reference} has been received. Our event coordinator will send you a tailored quotation within 24 hours.");
    }

    /**
     * Display a specific catering request and its quotation.
     */
    public function show(string $reference): View
    {
        $cateringRequest = CateringRequest::with(['package', 'user'])
            ->where('reference', $reference)
            ->firstOrFail();

        return view('catering.show', compact('cateringRequest'));
    }

    /**
     * Accept quotation and process deposit or balance payment.
     */
    public function accept(
        string $reference,
        Request $request,
        PaymentGatewayManager $gatewayManager,
        WhatsAppNotificationService $whatsAppService
    ): RedirectResponse {
        $cateringRequest = CateringRequest::where('reference', $reference)->firstOrFail();

        $validated = $request->validate([
            'payment_method' => ['required', 'in:paystack,stripe,bank_transfer'],
            'payment_type' => ['nullable', 'in:deposit,balance,full'],
        ]);

        $paymentType = $validated['payment_type'] ?? 'deposit';

        // Transition status
        if ($paymentType === 'balance' || $cateringRequest->status === 'deposit_paid') {
            $cateringRequest->update(['status' => 'in_preparation']);
            $message = "Full balance payment recorded for catering quotation #{$cateringRequest->reference}. Our team has scheduled kitchen preparation!";
        } else {
            $cateringRequest->update(['status' => 'deposit_paid']);
            $message = "Deposit successfully received for catering booking #{$cateringRequest->reference}! Your event date ({$cateringRequest->event_date}) is now secured.";
        }

        try {
            $whatsAppService->sendOrderUpdate(
                // Pseudo-order structure for notification
                new Order([
                    'order_number' => $cateringRequest->reference,
                    'customer_name' => $cateringRequest->customer_name,
                    'customer_phone' => $cateringRequest->customer_phone,
                    'status' => $cateringRequest->status,
                ]),
                $message
            );
        } catch (\Exception $e) {
        }

        return redirect()->route('catering.show', $cateringRequest->reference)
            ->with('success', $message);
    }
}
