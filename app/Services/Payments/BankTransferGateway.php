<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Http\Request;

class BankTransferGateway implements PaymentGatewayInterface
{
    public function initiatePayment(Order $order): array
    {
        $reference = 'BANK_'.$order->order_number;

        return [
            'status' => 'success',
            'reference' => $reference,
            'checkout_url' => route('orders.bank-transfer', $order->order_number),
            'message' => 'Please transfer to our designated kitchen bank account.',
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return [
            'success' => true,
            'status' => 'pending_manual_confirmation',
            'reference' => $reference,
            'amount_minor' => 0,
            'currency' => 'NGN',
            'message' => 'Bank transfer pending manual audit.',
        ];
    }

    public function handleWebhook(Request $request): array
    {
        return [
            'handled' => false,
            'event' => 'bank_transfer_offline',
            'reference' => null,
            'order_number' => null,
            'status' => 'pending',
            'amount_minor' => 0,
            'currency' => 'NGN',
        ];
    }
}
