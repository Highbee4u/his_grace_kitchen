<?php

namespace App\Services\Payments;

use InvalidArgumentException;

class PaymentGatewayManager
{
    /**
     * Resolve a payment gateway instance by gateway slug.
     */
    public function gateway(?string $gateway = null): PaymentGatewayInterface
    {
        $gateway = strtolower($gateway ?? 'paystack');

        return match ($gateway) {
            'paystack' => app(PaystackPaymentGateway::class),
            'stripe' => app(StripePaymentGateway::class),
            'bank_transfer' => app(BankTransferGateway::class),
            'pay_on_delivery', 'pod' => app(PayOnDeliveryGateway::class),
            default => throw new InvalidArgumentException("Unsupported payment gateway: {$gateway}"),
        };
    }
}
