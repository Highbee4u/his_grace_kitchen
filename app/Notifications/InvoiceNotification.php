<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Invoice $invoice,
        public ?string $pdfContent = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $downloadUrl = route('invoices.download', $this->invoice->number);
        $totalFormatted = Money::format($this->invoice->total_minor, $this->invoice->currency);

        $mail = (new MailMessage)
            ->subject("Invoice #{$this->invoice->number} - His Grace Kitchen LTD")
            ->greeting('Hello,')
            ->line("Thank you for your business with His Grace Kitchen LTD. Please find your invoice #{$this->invoice->number} details below.")
            ->line("**Invoice Amount:** {$totalFormatted}")
            ->line('**Status:** '.strtoupper($this->invoice->status))
            ->action('Download Invoice PDF', $downloadUrl)
            ->line('A printable PDF copy is also attached to this email for your accounting records.');

        if ($this->pdfContent) {
            $mail->attachData($this->pdfContent, "Invoice-{$this->invoice->number}.pdf", [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
