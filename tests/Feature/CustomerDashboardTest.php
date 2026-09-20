<?php

namespace Tests\Feature;

use App\Models\CateringRequest;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Database\Seeders\RoleAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndSettingsSeeder::class);
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_customer_can_view_dashboard_with_orders_and_stats(): void
    {
        $customer = User::factory()->create([
            'name' => 'Chioma Adeyemi',
            'email' => 'chioma@example.com',
        ]);

        $zone = DeliveryZone::create([
            'name' => 'Lagos Mainland',
            'fee_minor' => 250000,
            'currency' => 'NGN',
            'lead_time_minutes' => 45,
            'allow_pay_on_delivery' => true,
            'is_active' => true,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'HGK-2026-9901',
            'customer_name' => 'Chioma Adeyemi',
            'customer_email' => 'chioma@example.com',
            'customer_phone' => '+2348011223344',
            'fulfilment_type' => 'delivery',
            'delivery_zone_id' => $zone->id,
            'subtotal_minor' => 1200000,
            'delivery_fee_minor' => 250000,
            'discount_minor' => 0,
            'total_minor' => 1450000,
            'currency' => 'NGN',
            'status' => 'preparing',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'name' => 'Smoky Firewood Jollof Feast',
            'quantity' => 2,
            'unit_price_minor' => 600000,
            'total_minor' => 1200000,
        ]);

        CateringRequest::create([
            'user_id' => $customer->id,
            'reference' => 'CAT-TEST-001',
            'customer_name' => 'Chioma Adeyemi',
            'customer_email' => 'chioma@example.com',
            'customer_phone' => '+2348011223344',
            'guest_count' => 50,
            'event_date' => now()->addDays(14)->toDateString(),
            'venue' => 'Ikeja GRA, Lagos',
            'status' => 'quote_sent',
        ]);

        $response = $this->actingAs($customer)->get('/dashboard');

        $response->assertStatus(200)
            ->assertSee('Welcome, Chioma Adeyemi')
            ->assertSee('HGK-2026-9901')
            ->assertSee('Smoky Firewood Jollof Feast')
            ->assertSee('CAT-TEST-001')
            ->assertSee('Active Deliveries');
    }

    public function test_staff_user_is_redirected_to_admin_panel_from_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@hisgracekitchen.test',
        ]);
        $admin->assignRole('super_admin');

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect('/admin');
    }

    public function test_storefront_navbar_renders_streamlined_non_wrapping_labels(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200)
            ->assertSee('Menu')
            ->assertSee('Combos')
            ->assertSee('Catering')
            ->assertSee('Special Requests')
            ->assertSee('About')
            ->assertSee('Contact');
    }
}
