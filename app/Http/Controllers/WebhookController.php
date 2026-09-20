<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Notifications\OrderPaidNotification;
use App\Services\Payments\PaystackPaymentGateway;
use App\Services\Payments\StripePaymentGateway;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class WebhookController extends Controller
{
    /**
     * Handle Paystack incoming webhooks.
     */
    public function handlePaystack(
        Request $request,
        PaystackPaymentGateway $gateway,
        WhatsAppNotificationService $whatsAppService
    ): JsonResponse {
        $result = $gateway->handleWebhook($request);

        if (! $result['handled']) {
            return response()->json(['status' => 'ignored', 'message' => $result['event']], 200);
        }

        if (! empty($result['order_number'])) {
            $order = Order::where('order_number', $result['order_number'])->first();

            if ($order && $order->status === 'pending_payment') {
                $order->update(['status' => 'paid']);

                Payment::updateOrCreate(
                    ['order_id' => $order->id, 'gateway' => 'paystack'],
                    [
                        'reference' => $result['reference'] ?? ('PSTK_'.$order->order_number),
                        'status' => 'successful',
                        'amount_minor' => $result['amount_minor'] ?: $order->total_minor,
                        'currency' => $result['currency'],
                        'paid_at' => now(),
                    ]
                );

                try {
                    if ($order->user) {
                        $order->user->notify(new OrderPaidNotification($order));
                    } else {
                        Notification::route('mail', $order->customer_email)
                            ->notify(new OrderPaidNotification($order));
                    }
                    $whatsAppService->sendOrderUpdate($order, "Payment verified via Paystack for Order #{$order->order_number}. The kitchen is preparing your dishes!");
                } catch (\Exception $e) {
                    // Ignore notification errors in webhook responses
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle Stripe incoming webhooks.
     */
    public function handleStripe(
        Request $request,
        StripePaymentGateway $gateway,
        WhatsAppNotificationService $whatsAppService
    ): JsonResponse {
        $result = $gateway->handleWebhook($request);

        if (! $result['handled']) {
            return response()->json(['status' => 'ignored', 'message' => $result['event']], 200);
        }

        if (! empty($result['order_number'])) {
            $order = Order::where('order_number', $result['order_number'])->first();

            if ($order && $order->status === 'pending_payment') {
                $order->update(['status' => 'paid']);

                Payment::updateOrCreate(
                    ['order_id' => $order->id, 'gateway' => 'stripe'],
                    [
                        'reference' => $result['reference'] ?? ('STRIPE_'.$order->order_number),
                        'status' => 'successful',
                        'amount_minor' => $result['amount_minor'] ?: $order->total_minor,
                        'currency' => $result['currency'],
                        'paid_at' => now(),
                    ]
                );

                try {
                    if ($order->user) {
                        $order->user->notify(new OrderPaidNotification($order));
                    } else {
                        Notification::route('mail', $order->customer_email)
                            ->notify(new OrderPaidNotification($order));
                    }
                    $whatsAppService->sendOrderUpdate($order, "International payment verified via Stripe for Order #{$order->order_number}. Your diaspora express pack is being prepared!");
                } catch (\Exception $e) {
                    // Ignore notification errors
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
