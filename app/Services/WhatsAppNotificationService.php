<?php

namespace App\Services\Payments; // Or Services

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected string $driver;

    protected ?string $apiKey;

    protected ?string $senderId;

    public function __construct()
    {
        $this->driver = config('services.whatsapp.driver', env('WHATSAPP_DRIVER', 'log'));
        $this->apiKey = config('services.whatsapp.api_key', env('WHATSAPP_API_KEY'));
        $this->senderId = config('services.whatsapp.sender_id', env('WHATSAPP_SENDER_ID'));
    }

    /**
     * Send an order status update via WhatsApp/SMS.
     */
    public function sendOrderUpdate(Order $order, ?string $customMessage = null): bool
    {
        $phone = $order->customer_phone;
        $statusText = ucfirst(str_replace('_', ' ', $order->status));
        $trackingUrl = route('orders.show', $order->order_number);

        $message = $customMessage ?? "Hello {$order->customer_name}, your Nigerian Kitchen order #{$order->order_number} is now: {$statusText}. Track here: {$trackingUrl}";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send raw SMS / WhatsApp message through the configured driver.
     */
    public function sendMessage(string $recipientPhone, string $message): bool
    {
        $normalizedPhone = preg_replace('/[^0-9+]/', '', $recipientPhone);

        if ($this->driver === 'log' || empty($this->apiKey) || app()->environment('testing')) {
            Log::info("WhatsApp/SMS Notification to [{$normalizedPhone}]: {$message}");

            return true;
        }

        if ($this->driver === 'termii') {
            try {
                $response = Http::timeout(10)->post('https://api.ng.termii.com/api/sms/send', [
                    'to' => $normalizedPhone,
                    'from' => $this->senderId ?? 'NigKitchen',
                    'sms' => $message,
                    'type' => 'plain',
                    'channel' => 'generic',
                    'api_key' => $this->apiKey,
                ]);

                return $response->successful();
            } catch (\Exception $e) {
                Log::error('Termii WhatsApp dispatch failed: '.$e->getMessage());

                return false;
            }
        }

        return true;
    }
}
