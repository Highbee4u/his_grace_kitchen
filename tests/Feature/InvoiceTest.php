<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Order;
use App\Notifications\InvoiceNotification;
use App\Services\InvoiceService;
use Database\Seeders\RoleAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected InvoiceService $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndSettingsSeeder::class);
        $this->invoiceService = app(InvoiceService::class);
    }

    public function test_invoice_can_be_generated_from_order(): void
    {
        $order = Order::create([
            'order_number' => 'NK-INV-TEST-1',
            'status' => 'paid',
            'fulfilment_type' => 'delivery',
            'customer_name' => 'Alhaji Dangote',
            'customer_email' => 'dangote@example.com',
            'customer_phone' => '+2348011111111',
            'subtotal_minor' => 1500000, // ₦15,000
            'delivery_fee_minor' => 350000, // ₦3,500
            'total_minor' => 1850000,
            'currency' => 'NGN',
            'notes' => 'Executive lunch banquet',
        ]);

        $order->items()->create([
            'name' => 'Smoky Party Jollof',
            'quantity' => 3,
            'unit_price_minor' => 500000,
            'total_minor' => 1500000,
            'options' => ['variant' => 'Roasted Turkey Wing'],
        ]);

        $invoice = $this->invoiceService->createFromOrder($order);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'order_id' => $order->id,
            'subtotal_minor' => 1500000,
            'delivery_fee_minor' => 350000,
            'total_minor' => 1850000,
            'status' => 'paid',
        ]);

        $this->assertStringStartsWith('INV-'.date('Y'), $invoice->number);
        $this->assertCount(1, $invoice->line_items);
        $this->assertEquals('Smoky Party Jollof', $invoice->line_items[0]['name']);
    }

    public function test_invoice_pdf_renders_and_downloads_successfully(): void
    {
        $invoice = Invoice::create([
            'number' => 'INV-2026-99991',
            'status' => 'paid',
            'subtotal_minor' => 800000,
            'total_minor' => 800000,
            'currency' => 'NGN',
            'line_items' => [
                ['name' => 'Egusi Soup & Pounded Yam', 'quantity' => 2, 'unit_price_minor' => 400000, 'total_minor' => 800000],
            ],
            'notes' => 'Authentic Nigerian dining',
        ]);

        $response = $this->get(route('invoices.download', $invoice->number));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
    }

    public function test_invoice_pdf_streams_inline(): void
    {
        $invoice = Invoice::create([
            'number' => 'INV-2026-99992',
            'status' => 'sent',
            'subtotal_minor' => 1200000,
            'total_minor' => 1200000,
            'currency' => 'NGN',
            'line_items' => [
                ['name' => 'Lagos Owambe Box', 'quantity' => 1, 'unit_price_minor' => 1200000, 'total_minor' => 1200000],
            ],
        ]);

        $response = $this->get(route('invoices.stream', $invoice->number));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
    }

    public function test_order_invoice_route_generates_and_downloads_pdf(): void
    {
        $order = Order::create([
            'order_number' => 'NK-ORDER-PDF-1',
            'status' => 'paid',
            'fulfilment_type' => 'pickup',
            'customer_name' => 'Femi Otedola',
            'customer_email' => 'femi@example.com',
            'customer_phone' => '+2348022222222',
            'subtotal_minor' => 2000000,
            'total_minor' => 2000000,
            'currency' => 'NGN',
        ]);

        $order->items()->create([
            'name' => 'Suya & Street Chops Platter',
            'quantity' => 1,
            'unit_price_minor' => 2000000,
            'total_minor' => 2000000,
        ]);

        $response = $this->get(route('orders.invoice', $order->order_number));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));

        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'total_minor' => 2000000,
        ]);
    }

    public function test_manual_invoice_creation(): void
    {
        $invoice = $this->invoiceService->createManual([
            'status' => 'sent',
            'subtotal_minor' => 50000000, // ₦500,000 corporate catering
            'delivery_fee_minor' => 5000000,
            'total_minor' => 55000000,
            'currency' => 'NGN',
            'line_items' => [
                ['name' => 'Executive Corporate Luncheon (50 guests)', 'quantity' => 50, 'unit_price_minor' => 1000000, 'total_minor' => 50000000],
            ],
            'notes' => 'Board meeting buffet with live suya chef',
        ]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'total_minor' => 55000000,
            'status' => 'sent',
        ]);
    }

    public function test_invoice_email_notification_dispatch(): void
    {
        Notification::fake();

        $invoice = Invoice::create([
            'number' => 'INV-2026-99993',
            'status' => 'paid',
            'subtotal_minor' => 500000,
            'total_minor' => 500000,
            'currency' => 'NGN',
        ]);

        $sent = $this->invoiceService->sendInvoiceEmail($invoice, 'client@example.com');

        $this->assertTrue($sent);

        Notification::assertSentOnDemand(
            InvoiceNotification::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'client@example.com' &&
                    $notification->invoice->number === 'INV-2026-99993' &&
                    ! empty($notification->pdfContent);
            }
        );
    }
}
