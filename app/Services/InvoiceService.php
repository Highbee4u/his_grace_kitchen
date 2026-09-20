<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Notifications\InvoiceNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class InvoiceService
{
    /**
     * Generate or retrieve an invoice from an existing Order.
     */
    public function createFromOrder(Order $order): Invoice
    {
        if ($order->invoice) {
            return $order->invoice;
        }

        $invoiceNumber = 'INV-'.date('Y').'-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT);

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'name' => $item->name,
                'variant' => $item->options['variant'] ?? null,
                'quantity' => $item->quantity,
                'unit_price_minor' => $item->unit_price_minor,
                'total_minor' => $item->total_minor,
            ];
        }

        $isPaid = in_array($order->status, ['paid', 'confirmed', 'preparing', 'ready_for_pickup', 'out_for_delivery', 'delivered']);

        return Invoice::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'number' => $invoiceNumber,
            'status' => $isPaid ? 'paid' : 'sent',
            'subtotal_minor' => (int) ($order->subtotal_minor ?? 0),
            'discount_minor' => (int) ($order->discount_minor ?? 0),
            'delivery_fee_minor' => (int) ($order->delivery_fee_minor ?? 0),
            'tax_minor' => (int) ($order->tax_minor ?? 0),
            'total_minor' => (int) ($order->total_minor ?? 0),
            'currency' => $order->currency ?? 'NGN',
            'line_items' => $lineItems,
            'notes' => $order->notes ?? 'Thank you for choosing Nigerian Kitchen!',
            'due_date' => now()->addDays(7)->toDateString(),
            'paid_at' => $isPaid ? now() : null,
        ]);
    }

    /**
     * Create a manual invoice (e.g. for catering, corporate accounts, or special requests).
     */
    public function createManual(array $data): Invoice
    {
        $year = date('Y');
        $count = Invoice::whereYear('created_at', $year)->count() + 1;
        $invoiceNumber = 'INV-'.$year.'-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);

        return Invoice::create([
            'order_id' => $data['order_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'number' => $invoiceNumber,
            'status' => $data['status'] ?? 'draft',
            'subtotal_minor' => $data['subtotal_minor'] ?? 0,
            'discount_minor' => $data['discount_minor'] ?? 0,
            'delivery_fee_minor' => $data['delivery_fee_minor'] ?? 0,
            'tax_minor' => $data['tax_minor'] ?? 0,
            'total_minor' => $data['total_minor'] ?? 0,
            'currency' => $data['currency'] ?? 'NGN',
            'line_items' => $data['line_items'] ?? [],
            'notes' => $data['notes'] ?? null,
            'due_date' => $data['due_date'] ?? now()->addDays(14)->toDateString(),
            'paid_at' => ($data['status'] ?? '') === 'paid' ? now() : null,
        ]);
    }

    /**
     * Render the invoice into a DomPDF instance.
     */
    public function generatePdf(Invoice $invoice): DomPdf
    {
        $invoice->loadMissing(['order.user', 'user']);

        return Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);
    }

    /**
     * Send the invoice via email with PDF attached.
     */
    public function sendInvoiceEmail(Invoice $invoice, ?string $recipientEmail = null): bool
    {
        $email = $recipientEmail ?? $invoice->order?->customer_email ?? $invoice->user?->email;

        if (! $email) {
            return false;
        }

        try {
            $pdf = $this->generatePdf($invoice);
            Notification::route('mail', $email)
                ->notify(new InvoiceNotification($invoice, $pdf->output()));

            return true;
        } catch (\Exception $e) {
            Log::warning('Invoice email failed: '.$e->getMessage());

            return false;
        }
    }
}
