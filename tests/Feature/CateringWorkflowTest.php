<?php

namespace Tests\Feature;

use App\Models\CateringPackage;
use App\Models\CateringRequest;
use App\Models\SpecialRequest;
use Database\Seeders\RoleAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CateringWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected CateringPackage $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndSettingsSeeder::class);

        $this->package = CateringPackage::create([
            'name' => 'Owambe Wedding Gala',
            'slug' => 'owambe-wedding-gala',
            'description' => 'Full buffet spread with live suya station',
            'pricing_model' => 'per_head',
            'price_minor' => 1800000,
            'includes' => ['Live Suya Station', 'Soup Bar'],
            'is_active' => true,
        ]);
    }

    public function test_catering_quote_review_page_renders_for_submitted_request(): void
    {
        $request = CateringRequest::create([
            'reference' => 'CAT-TEST-001',
            'customer_name' => 'Chief Funke Akindele',
            'customer_email' => 'funke@example.com',
            'customer_phone' => '+2348011223344',
            'catering_package_id' => $this->package->id,
            'guest_count' => 200,
            'event_date' => now()->addMonth()->format('Y-m-d'),
            'venue' => 'Eko Hotel Convention Centre',
            'status' => 'submitted',
        ]);

        $response = $this->get(route('catering.show', $request->reference));

        $response->assertStatus(200)
            ->assertSee('CAT-TEST-001')
            ->assertSee('Chief Funke Akindele')
            ->assertSee('200 Attendees')
            ->assertSee('Quotation In Preparation');
    }

    public function test_catering_quote_review_renders_quotation_and_deposit_breakdown_when_quoted(): void
    {
        $request = CateringRequest::create([
            'reference' => 'CAT-TEST-002',
            'customer_name' => 'Dr. Kunle Afolayan',
            'customer_email' => 'kunle@example.com',
            'customer_phone' => '+2348022334455',
            'catering_package_id' => $this->package->id,
            'guest_count' => 100,
            'event_date' => now()->addDays(20)->format('Y-m-d'),
            'venue' => 'Landmark Beach Resort',
            'status' => 'quote_sent',
            'quoted_total_minor' => 180000000, // ₦1,800,000
            'deposit_minor' => 90000000, // ₦900,000 (50%)
            'currency' => 'NGN',
            'admin_notes' => 'Includes 3 live grilling chefs and executive chaffing sets.',
        ]);

        $response = $this->get(route('catering.show', $request->reference));

        $response->assertStatus(200)
            ->assertSee('CAT-TEST-002')
            ->assertSee('Official Catering Quotation')
            ->assertSee('Accept Quotation', false)
            ->assertSee('Paystack (NGN)')
            ->assertSee('Stripe Diaspora');
    }

    public function test_customer_can_accept_catering_quotation_and_pay_deposit(): void
    {
        $request = CateringRequest::create([
            'reference' => 'CAT-TEST-003',
            'customer_name' => 'Mrs. Toyin Abraham',
            'customer_email' => 'toyin@example.com',
            'customer_phone' => '+2348033445566',
            'catering_package_id' => $this->package->id,
            'guest_count' => 150,
            'event_date' => now()->addDays(30)->format('Y-m-d'),
            'venue' => 'Federal Palace Hotel',
            'status' => 'quote_sent',
            'quoted_total_minor' => 270000000,
            'deposit_minor' => 135000000,
        ]);

        $response = $this->post(route('catering.accept', $request->reference), [
            'payment_method' => 'paystack',
            'payment_type' => 'deposit',
        ]);

        $response->assertRedirect(route('catering.show', $request->reference))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('catering_requests', [
            'reference' => 'CAT-TEST-003',
            'status' => 'deposit_paid',
        ]);
    }

    public function test_customer_can_pay_catering_remaining_balance(): void
    {
        $request = CateringRequest::create([
            'reference' => 'CAT-TEST-004',
            'customer_name' => 'Senator Bukola',
            'customer_email' => 'bukola@example.com',
            'customer_phone' => '+2348044556677',
            'catering_package_id' => $this->package->id,
            'guest_count' => 100,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
            'venue' => 'Transcorp Hilton Abuja',
            'status' => 'deposit_paid',
            'quoted_total_minor' => 180000000,
            'deposit_minor' => 90000000,
        ]);

        $response = $this->post(route('catering.accept', $request->reference), [
            'payment_method' => 'paystack',
            'payment_type' => 'balance',
        ]);

        $response->assertRedirect(route('catering.show', $request->reference))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('catering_requests', [
            'reference' => 'CAT-TEST-004',
            'status' => 'in_preparation',
        ]);
    }

    public function test_special_request_quote_review_page_renders_and_accepts_payment(): void
    {
        $specialRequest = SpecialRequest::create([
            'reference' => 'REQ-TEST-001',
            'customer_name' => 'Chioma Rowland',
            'customer_email' => 'chioma@example.com',
            'customer_phone' => '+2348055667788',
            'description' => '5-Litre Pot of Rivers Native Fisherman Soup with jumbo tiger prawns and river snails.',
            'quantity' => 2,
            'status' => 'quote_sent',
            'quoted_total_minor' => 6500000, // ₦65,000
            'admin_notes' => 'Market sourcing confirmed for fresh tiger prawns and dry fish.',
        ]);

        // Review view
        $viewResponse = $this->get(route('special-requests.show', $specialRequest->reference));
        $viewResponse->assertStatus(200)
            ->assertSee('REQ-TEST-001')
            ->assertSee('Rivers Native Fisherman Soup')
            ->assertSee('Accept Quote', false);

        // Accept & pay
        $acceptResponse = $this->post(route('special-requests.accept', $specialRequest->reference), [
            'payment_method' => 'paystack',
        ]);

        $acceptResponse->assertRedirect(route('special-requests.show', $specialRequest->reference))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('special_requests', [
            'reference' => 'REQ-TEST-001',
            'status' => 'in_preparation',
        ]);
    }
}
