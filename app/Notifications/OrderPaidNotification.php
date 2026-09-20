<?php

namespace App\Notifications;

use App\Models\Order;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $trackingUrl = route('orders.show', $this->order->order_number);
        $formattedTotal = Money::format($this->order->total_minor, $this->order->currency ?? 'NGN');

        return (new MailMessage)
            ->subject("Payment Confirmed: Order #{$this->order->order_number} - His Grace Kitchen LTD")
            ->greeting("Hello {$this->order->customer_name},")
            ->line("Good news! Your payment of {$formattedTotal} for Order #{$this->order->order_number} has been verified.")
            ->line('Our kitchen team is now preparing your delicious meal with the freshest native ingredients.')
            ->action('Track Preparation & Delivery', $trackingUrl)
            ->line('Thank you for choosing His Grace Kitchen LTD!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => 'paid',
            'amount_minor' => $this->order->total_minor,
            'currency' => $this->order->currency,
        ];
    }
}
