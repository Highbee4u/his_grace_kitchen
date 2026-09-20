<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SpecialRequest;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\WhatsAppNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SpecialRequestController extends Controller
{
    /**
     * Show the special off-menu dish request form.
     */
    public function create(): View
    {
        return view('special-requests.create');
    }

    /**
     * Store a custom dish request.
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
            'description' => ['required', 'string', 'max:2000'],
            'quantity' => ['required', 'integer', 'min:1'],
            'needed_by' => ['nullable', 'date', 'after:today'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'reference_image_url' => ['nullable', 'url', 'max:500'],
        ]);

        $reference = 'REQ-'.strtoupper(uniqid());

        SpecialRequest::create([
            'user_id' => auth()->id(),
            'reference' => $reference,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'description' => $validated['description'],
            'quantity' => $validated['quantity'],
            'needed_by' => $validated['needed_by'] ?? null,
            'budget_minor' => isset($validated['budget']) ? (int) round($validated['budget'] * 100) : null,
            'reference_image_url' => $validated['reference_image_url'] ?? null,
            'status' => 'submitted',
            'currency' => 'NGN',
        ]);

        return redirect()->route('special-requests.create')
            ->with('success', "Your special request #{$reference} has been sent to the kitchen! Our head chef will review ingredient availability and send you a quote.");
    }

    /**
     * Display a specific special off-menu dish request and its quotation.
     */
    public function show(string $reference): View
    {
        $specialRequest = SpecialRequest::where('reference', $reference)->firstOrFail();

        return view('special-requests.show', compact('specialRequest'));
    }

    /**
     * Accept quotation and process payment for off-menu dish.
     */
    public function accept(
        string $reference,
        Request $request,
        PaymentGatewayManager $gatewayManager,
        WhatsAppNotificationService $whatsAppService
    ): RedirectResponse {
        $specialRequest = SpecialRequest::where('reference', $reference)->firstOrFail();

        $validated = $request->validate([
            'payment_method' => ['required', 'in:paystack,stripe,bank_transfer'],
        ]);

        $specialRequest->update(['status' => 'in_preparation']);
        $message = "Payment confirmed for special request #{$specialRequest->reference}! Our head chef has sourced the native ingredients and started cooking.";

        try {
            $whatsAppService->sendOrderUpdate(
                new Order([
                    'order_number' => $specialRequest->reference,
                    'customer_name' => $specialRequest->customer_name,
                    'customer_phone' => $specialRequest->customer_phone,
                    'status' => 'in_preparation',
                ]),
                $message
            );
        } catch (\Exception $e) {
        }

        return redirect()->route('special-requests.show', $specialRequest->reference)
            ->with('success', $message);
    }
}
