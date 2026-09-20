<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Initiate a payment session for an order.
     *
     * @return array{
     *     status: string,
     *     reference: string,
     *     checkout_url: ?string,
     *     message: ?string,
     *     data?: array
     * }
     */
    public function initiatePayment(Order $order): array;

    /**
     * Verify payment status using gateway reference.
     *
     * @return array{
     *     success: bool,
     *     status: string,
     *     reference: string,
     *     amount_minor: int,
     *     currency: string,
     *     message?: string
     * }
     */
    public function verifyPayment(string $reference): array;

    /**
     * Handle incoming gateway webhook request.
     *
     * @return array{
     *     handled: bool,
     *     event: string,
     *     reference: ?string,
     *     order_number: ?string,
     *     status: string,
     *     amount_minor: int,
     *     currency: string
     * }
     */
    public function handleWebhook(Request $request): array;
}
