<?php

namespace App\Notifications;

use App\Models\CateringRequest;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CateringQuoteSentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public CateringRequest $cateringRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quoteUrl = route('catering.show', $this->cateringRequest->reference);
        $totalFormatted = Money::format($this->cateringRequest->quoted_total_minor, $this->cateringRequest->currency ?? 'NGN');
        $depositFormatted = Money::format($this->cateringRequest->deposit_minor, $this->cateringRequest->currency ?? 'NGN');

        return (new MailMessage)
            ->subject("Event Catering Quotation #{$this->cateringRequest->reference} - His Grace Kitchen LTD")
            ->greeting("Hello {$this->cateringRequest->customer_name},")
            ->line("Our culinary event director has prepared your customized catering quotation for your event on {$this->cateringRequest->event_date}.")
            ->line("**Quoted Total:** {$totalFormatted}")
            ->line("**Required Deposit to Secure Date:** {$depositFormatted}")
            ->action('Review & Accept Quotation', $quoteUrl)
            ->line('Our catering slots book quickly for peak Owambe dates. Please review and secure your date with a deposit.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'catering_request_id' => $this->cateringRequest->id,
            'reference' => $this->cateringRequest->reference,
            'status' => 'quote_sent',
            'quoted_total_minor' => $this->cateringRequest->quoted_total_minor,
            'deposit_minor' => $this->cateringRequest->deposit_minor,
        ];
    }
}
