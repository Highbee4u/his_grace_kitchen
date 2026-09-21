<?php

namespace Tests\Feature;

use App\Models\AddOn;
use App\Models\DeliveryZone;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use App\Models\Order;
use Database\Seeders\RoleAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected DeliveryZone $zoneWithPod;

    protected DeliveryZone $zoneWithoutPod;

    protected MenuItem $jollofItem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndSettingsSeeder::class);

        $category = MenuCategory::create([
            'name' => 'Rice Dishes',
            'slug' => 'rice-dishes',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->jollofItem = MenuItem::create([
            'menu_category_id' => $category->id,
            'name' => 'Smoky Party Jollof',
            'slug' => 'smoky-party-jollof',
            'price_minor' => 450000, // ₦4,500
            'is_available' => true,
        ]);

        $this->zoneWithPod = DeliveryZone::create([
            'name' => 'Lagos Island Express',
            'fee_minor' => 350000, // ₦3,500
            'currency' => 'NGN',
            'lead_time_minutes' => 45,
            'allow_pay_on_delivery' => true,
            'is_active' => true,
        ]);

        $this->zoneWithoutPod = DeliveryZone::create([
            'name' => 'UK Express Courier',
            'fee_minor' => 4500000, // ₦45,000
            'currency' => 'NGN',
            'lead_time_minutes' => 2880,
            'allow_pay_on_delivery' => false,
            'is_active' => true,
        ]);
    }

    public function test_checkout_page_renders_with_delivery_zones(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertStatus(200)
            ->assertSee('Lagos Island Express')
            ->assertSee('UK Express Courier')
            ->assertSee('Paystack')
            ->assertSee('Stripe');
    }

    public function test_guest_can_place_delivery_order_with_valid_items(): void
    {
        $payload = [
            'customer_name' => 'Amaka Obi',
            'customer_email' => 'amaka@example.com',
            'customer_phone' => '+2348012345678',
            'fulfilment_type' => 'delivery',
            'delivery_zone_id' => $this->zoneWithPod->id,
            'delivery_address_street' => '12 Admiralty Road',
            'delivery_address_city' => 'Lekki Phase 1',
            'delivery_address_state' => 'Lagos State',
            'payment_method' => 'paystack',
            'cart_items' => json_encode([
                [
                    'id' => $this->jollofItem->id,
                    'name' => 'Smoky Party Jollof',
                    'price' => 450000,
                    'quantity' => 2,
                    'variant' => 'Fried Chicken Lap',
                ],
            ]),
            'notes' => 'Please make it extra spicy',
        ];

        $response = $this->post(route('checkout.store'), $payload);

        // Expected totals:
        // Subtotal: 2 * 4,500 = ₦9,000 (900,000 kobo)
        // Delivery fee: ₦3,500 (350,000 kobo)
        // Total: ₦12,500 (1,250,000 kobo)
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Amaka Obi',
            'customer_email' => 'amaka@example.com',
            'subtotal_minor' => 900000,
            'delivery_fee_minor' => 350000,
            'total_minor' => 1250000,
            'status' => 'pending_payment',
        ]);

        $order = Order::where('customer_email', 'amaka@example.com')->first();
        $this->assertNotNull($order);
        $this->assertCount(1, $order->items);
        $this->assertEquals(2, $order->items->first()->quantity);
        $this->assertEquals(450000, $order->items->first()->unit_price_minor);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway' => 'paystack',
            'status' => 'pending',
        ]);

        $response->assertRedirect();
    }

    public function test_pickup_order_applies_zero_delivery_fee(): void
    {
        $payload = [
            'customer_name' => 'Emeka Nwosu',
            'customer_email' => 'emeka@example.com',
            'customer_phone' => '+2348088889999',
            'fulfilment_type' => 'pickup',
            'payment_method' => 'paystack',
            'cart_items' => json_encode([
                [
                    'id' => $this->jollofItem->id,
                    'name' => 'Smoky Party Jollof',
                    'price' => 450000,
                    'quantity' => 1,
                ],
            ]),
        ];

        $response = $this->post(route('checkout.store'), $payload);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Emeka Nwosu',
            'fulfilment_type' => 'pickup',
            'delivery_fee_minor' => 0,
            'total_minor' => 450000,
        ]);

        $response->assertRedirect();
    }

    public function test_checkout_charges_selected_variant_and_add_ons(): void
    {
        MenuItemVariant::create([
            'menu_item_id' => $this->jollofItem->id,
            'name' => 'Roasted Turkey Wing',
            'price_minor' => 80000,
            'is_default' => false,
            'is_available' => true,
        ]);
        AddOn::create([
            'name' => 'Extra Portion of Fried Plantain (Dodo)',
            'price_minor' => 80000,
            'is_available' => true,
        ]);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Amina Yusuf',
            'customer_email' => 'amina@example.com',
            'customer_phone' => '+2348011112222',
            'fulfilment_type' => 'pickup',
            'payment_method' => 'paystack',
            'cart_items' => json_encode([[
                'id' => $this->jollofItem->id,
                'variant' => 'Roasted Turkey Wing',
                'addOns' => ['Extra Portion of Fried Plantain (Dodo)'],
                'quantity' => 1,
            ]]),
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'amina@example.com',
            'subtotal_minor' => 610000,
            'total_minor' => 610000,
        ]);
    }

    public function test_pay_on_delivery_fails_for_disallowed_zone(): void
    {
        $payload = [
            'customer_name' => 'London Customer',
            'customer_email' => 'london@example.com',
            'customer_phone' => '+447911123456',
            'fulfilment_type' => 'delivery',
            'delivery_zone_id' => $this->zoneWithoutPod->id,
            'delivery_address_street' => '10 Oxford Street',
            'delivery_address_city' => 'London',
            'payment_method' => 'pay_on_delivery',
            'cart_items' => json_encode([
                [
                    'id' => $this->jollofItem->id,
                    'name' => 'Smoky Party Jollof',
                    'price' => 450000,
                    'quantity' => 1,
                ],
            ]),
        ];

        $response = $this->post(route('checkout.store'), $payload);

        $response->assertSessionHasErrors(['payment_method']);
        $this->assertDatabaseMissing('orders', ['customer_email' => 'london@example.com']);
    }

    public function test_pay_on_delivery_succeeds_and_confirms_order_for_allowed_zone(): void
    {
        $payload = [
            'customer_name' => 'Lagos Resident',
            'customer_email' => 'lagos@example.com',
            'customer_phone' => '+2348055554444',
            'fulfilment_type' => 'delivery',
            'delivery_zone_id' => $this->zoneWithPod->id,
            'delivery_address_street' => 'Victoria Island 5',
            'delivery_address_city' => 'Lagos',
            'payment_method' => 'pay_on_delivery',
            'cart_items' => json_encode([
                [
                    'id' => $this->jollofItem->id,
                    'name' => 'Smoky Party Jollof',
                    'price' => 450000,
                    'quantity' => 1,
                ],
            ]),
        ];

        $response = $this->post(route('checkout.store'), $payload);

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'lagos@example.com',
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
    }

    public function test_order_tracking_page_displays_order_details(): void
    {
        $order = Order::create([
            'order_number' => 'NK-TEST-001',
            'status' => 'preparing',
            'fulfilment_type' => 'delivery',
            'customer_name' => 'Bolanle Alabi',
            'customer_email' => 'bolanle@example.com',
            'customer_phone' => '+2348077778888',
            'subtotal_minor' => 450000,
            'delivery_fee_minor' => 350000,
            'total_minor' => 800000,
            'currency' => 'NGN',
        ]);

        $order->items()->create([
            'name' => 'Smoky Party Jollof',
            'quantity' => 1,
            'unit_price_minor' => 450000,
            'total_minor' => 450000,
        ]);

        // Direct show route
        $showResponse = $this->get(route('orders.show', $order->order_number));
        $showResponse->assertStatus(200)
            ->assertSee('NK-TEST-001')
            ->assertSee('Bolanle Alabi')
            ->assertSee('Smoky Party Jollof');

        // Lookup via search form
        $searchResponse = $this->post(route('orders.track'), [
            'order_number' => 'NK-TEST-001',
            'customer_email' => 'bolanle@example.com',
        ]);

        $searchResponse->assertRedirect(route('orders.show', 'NK-TEST-001'));
    }

    public function test_paystack_webhook_updates_order_to_paid(): void
    {
        $order = Order::create([
            'order_number' => 'NK-PAYSTACK-123',
            'status' => 'pending_payment',
            'fulfilment_type' => 'delivery',
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+2348000000000',
            'subtotal_minor' => 500000,
            'total_minor' => 500000,
            'currency' => 'NGN',
        ]);

        $payload = [
            'event' => 'charge.success',
            'data' => [
                'reference' => 'PSTK_REF_999',
                'amount' => 500000,
                'currency' => 'NGN',
                'metadata' => [
                    'order_number' => 'NK-PAYSTACK-123',
                ],
            ],
        ];

        $response = $this->postJson(route('webhooks.paystack'), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'order_number' => 'NK-PAYSTACK-123',
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway' => 'paystack',
            'status' => 'successful',
        ]);
    }

    public function test_stripe_webhook_updates_order_to_paid(): void
    {
        $order = Order::create([
            'order_number' => 'NK-STRIPE-456',
            'status' => 'pending_payment',
            'fulfilment_type' => 'delivery',
            'customer_name' => 'Diaspora Buyer',
            'customer_email' => 'diaspora@example.com',
            'customer_phone' => '+14045551234',
            'subtotal_minor' => 6500, // $65.00
            'total_minor' => 6500,
            'currency' => 'USD',
        ]);

        $payload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_stripe_session_456',
                    'amount_total' => 6500,
                    'currency' => 'usd',
                    'metadata' => [
                        'order_number' => 'NK-STRIPE-456',
                    ],
                ],
            ],
        ];

        $response = $this->postJson(route('webhooks.stripe'), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'order_number' => 'NK-STRIPE-456',
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway' => 'stripe',
            'status' => 'successful',
        ]);
    }

    public function test_bank_transfer_renders_instructions(): void
    {
        $order = Order::create([
            'order_number' => 'NK-BANK-789',
            'status' => 'pending_payment',
            'fulfilment_type' => 'delivery',
            'customer_name' => 'Bank Customer',
            'customer_email' => 'bank@example.com',
            'customer_phone' => '+2348099990000',
            'subtotal_minor' => 1000000,
            'total_minor' => 1000000,
            'currency' => 'NGN',
        ]);

        $response = $this->get(route('orders.bank-transfer', $order->order_number));

        $response->assertStatus(200)
            ->assertSee('NK-BANK-789')
            ->assertSee('Guaranty Trust Bank')
            ->assertSee('Zenith Bank')
            ->assertSee('0123456789');
    }

    public function test_checkout_respects_selected_currency(): void
    {
        $payload = [
            'customer_name' => 'Diaspora Customer',
            'customer_email' => 'diaspora@example.com',
            'customer_phone' => '+447911123456',
            'fulfilment_type' => 'pickup',
            'payment_method' => 'bank_transfer',
            'currency' => 'GBP',
            'cart_items' => json_encode([
                [
                    'id' => $this->jollofItem->id,
                    'name' => $this->jollofItem->name,
                    'price' => $this->jollofItem->price_minor,
                    'quantity' => 1,
                ],
            ]),
        ];

        $response = $this->post(route('checkout.store'), $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'customer_email' => 'diaspora@example.com',
            'currency' => 'GBP',
        ]);
    }
}
