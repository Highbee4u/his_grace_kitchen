<?php

namespace App\Notifications;

use App\Models\Order;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
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
            ->subject("Order #{$this->order->order_number} Received - Nigerian Kitchen")
            ->greeting("Hello {$this->order->customer_name},")
            ->line('Thank you for ordering with Nigerian Kitchen! We have received your order and our chefs are ready.')
            ->line("Order Number: #{$this->order->order_number}")
            ->line('Fulfillment: '.ucfirst($this->order->fulfilment_type))
            ->line("Order Total: {$formattedTotal}")
            ->action('Track Your Order', $trackingUrl)
            ->line('You can monitor live preparation and delivery status in real-time using the tracking link above.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_minor' => $this->order->total_minor,
            'currency' => $this->order->currency,
            'status' => $this->order->status,
        ];
    }
}
