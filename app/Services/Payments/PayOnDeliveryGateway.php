<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Http\Request;

class PayOnDeliveryGateway implements PaymentGatewayInterface
{
    public function initiatePayment(Order $order): array
    {
        $reference = 'POD_'.$order->order_number;

        // Automatically mark order as confirmed for cash/POS upon delivery
        $order->update(['status' => 'confirmed']);

        return [
            'status' => 'success',
            'reference' => $reference,
            'checkout_url' => route('orders.show', [
                'order_number' => $order->order_number,
                'pod' => 1,
            ]),
            'message' => 'Pay on delivery confirmed. Please have Cash or POS ready for the rider.',
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return [
            'success' => true,
            'status' => 'pending_delivery',
            'reference' => $reference,
            'amount_minor' => 0,
            'currency' => 'NGN',
            'message' => 'Pay on delivery will be collected at doorstep.',
        ];
    }

    public function handleWebhook(Request $request): array
    {
        return [
            'handled' => false,
            'event' => 'pod_offline',
            'reference' => null,
            'order_number' => null,
            'status' => 'pending',
            'amount_minor' => 0,
            'currency' => 'NGN',
        ];
    }
}
