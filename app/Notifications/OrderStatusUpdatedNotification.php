<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order, public string $previousStatus) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'preparing' => 'In The Kitchen (Preparing)',
            'ready_for_pickup' => 'Ready For Pickup at Central Kitchen',
            'out_for_delivery' => 'Dispatched / Out for Delivery with Rider',
            'delivered' => 'Delivered / Completed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
        ];

        $currentLabel = $statusLabels[$this->order->status] ?? ucfirst(str_replace('_', ' ', $this->order->status));
        $trackingUrl = route('orders.show', $this->order->order_number);

        return (new MailMessage)
            ->subject("Update on Order #{$this->order->order_number}: {$currentLabel}")
            ->greeting("Hello {$this->order->customer_name},")
            ->line('The status of your His Grace Kitchen LTD order has been updated:')
            ->line("**Current Status:** {$currentLabel}")
            ->action('View Live Status', $trackingUrl)
            ->line('Enjoy your delicious authentic meal!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'previous_status' => $this->previousStatus,
        ];
    }
}
