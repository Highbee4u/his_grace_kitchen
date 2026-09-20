<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Download the invoice PDF.
     */
    public function download(string $number, InvoiceService $invoiceService): Response
    {
        $invoice = Invoice::where('number', $number)->firstOrFail();

        return $invoiceService->generatePdf($invoice)
            ->download("Invoice-{$invoice->number}.pdf");
    }

    /**
     * Preview/stream the invoice PDF inline in the browser.
     */
    public function stream(string $number, InvoiceService $invoiceService): Response
    {
        $invoice = Invoice::where('number', $number)->firstOrFail();

        return $invoiceService->generatePdf($invoice)
            ->stream("Invoice-{$invoice->number}.pdf");
    }

    /**
     * Generate or download PDF invoice for a given order number.
     */
    public function orderInvoice(string $order_number, InvoiceService $invoiceService): Response
    {
        $order = Order::with('items')->where('order_number', $order_number)->firstOrFail();
        $invoice = $invoiceService->createFromOrder($order);

        return $invoiceService->generatePdf($invoice)
            ->download("Invoice-{$invoice->number}.pdf");
    }
}
