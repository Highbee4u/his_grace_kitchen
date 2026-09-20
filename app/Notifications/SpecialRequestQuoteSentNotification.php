<?php

namespace App\Notifications;

use App\Models\SpecialRequest;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpecialRequestQuoteSentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public SpecialRequest $specialRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quoteUrl = route('special-requests.show', $this->specialRequest->reference);
        $totalFormatted = Money::format($this->specialRequest->quoted_total_minor, $this->specialRequest->currency ?? 'NGN');

        return (new MailMessage)
            ->subject("Chef Quote Ready: Special Request #{$this->specialRequest->reference} - His Grace Kitchen LTD")
            ->greeting("Hello {$this->specialRequest->customer_name},")
            ->line('Our head chef has reviewed market ingredient availability for your custom dish request.')
            ->line("**Quoted Total:** {$totalFormatted}")
            ->action('View Quote & Confirm Order', $quoteUrl)
            ->line('Upon confirmation and secure payment, our kitchen will source fresh native ingredients and cook your special dish from scratch.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'special_request_id' => $this->specialRequest->id,
            'reference' => $this->specialRequest->reference,
            'status' => 'quote_sent',
            'quoted_total_minor' => $this->specialRequest->quoted_total_minor,
        ];
    }
}
