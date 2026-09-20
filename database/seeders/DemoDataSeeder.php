<?php

namespace Database\Seeders;

use App\Models\CateringPackage;
use App\Models\CateringRequest;
use App\Models\DeliveryZone;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\SpecialRequest;
use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds for demo orders, invoices, and workflow requests.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $lekkiZone = DeliveryZone::where('name', 'like', '%Lekki%')->first() ?? DeliveryZone::first();
        $ukZone = DeliveryZone::where('name', 'like', '%UK%')->first() ?? DeliveryZone::first();
        $mainlandZone = DeliveryZone::where('name', 'like', '%Mainland%')->first() ?? DeliveryZone::first();

        $jollof = MenuItem::where('slug', 'smoky-party-jollof-rice')->first();
        $egusi = MenuItem::where('slug', 'egusi-soup-pounded-yam')->first();
        $suya = MenuItem::where('slug', 'flame-grilled-beef-suya')->first();
        $chapman = MenuItem::where('slug', 'lagos-signature-chapman')->first();

        $invoiceService = app(InvoiceService::class);

        // 1. Order 1: Delivered (NGN, Paystack)
        $order1 = Order::updateOrCreate(
            ['order_number' => 'NK-2026-00101'],
            [
                'user_id' => $customer?->id,
                'delivery_zone_id' => $lekkiZone?->id,
                'status' => 'delivered',
                'fulfilment_type' => 'delivery',
                'customer_name' => 'Folake Adeleke',
                'customer_email' => 'customer@example.com',
                'customer_phone' => '+234 803 123 4567',
                'delivery_address' => [
                    'street' => 'Block 12, Admiralty Way',
                    'city' => 'Lekki Phase 1',
                    'state' => 'Lagos',
                    'country' => 'Nigeria',
                ],
                'subtotal_minor' => 1250000,
                'delivery_fee_minor' => 350000,
                'discount_minor' => 0,
                'tax_minor' => 0,
                'total_minor' => 1600000,
                'currency' => 'NGN',
                'notes' => 'Please include extra cutlery and spicy pepper sauce.',
            ]
        );

        if ($order1->wasRecentlyCreated || $order1->items()->count() === 0) {
            OrderItem::create([
                'order_id' => $order1->id,
                'menu_item_id' => $jollof?->id,
                'name' => 'Smoky Party Jollof Rice',
                'quantity' => 2,
                'unit_price_minor' => 450000,
                'total_minor' => 900000,
                'options' => ['variant' => 'Jollof with Smoked Chicken', 'add_ons' => ['Fried Dodo']],
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'menu_item_id' => $suya?->id,
                'name' => 'Flame-Grilled Beef Suya',
                'quantity' => 1,
                'unit_price_minor' => 350000,
                'total_minor' => 350000,
                'options' => ['spice' => 'Extra Yaji Pepper'],
            ]);

            Payment::create([
                'order_id' => $order1->id,
                'gateway' => 'paystack',
                'reference' => 'pstk_ref_' . uniqid(),
                'status' => 'success',
                'amount_minor' => 1600000,
                'currency' => 'NGN',
                'paid_at' => now()->subHours(5),
            ]);

            $invoiceService->createFromOrder($order1);
        }

        // 2. Order 2: Preparing (GBP, Stripe Diaspora Delivery to London)
        $order2 = Order::updateOrCreate(
            ['order_number' => 'NK-2026-00102'],
            [
                'user_id' => null,
                'delivery_zone_id' => $ukZone?->id,
                'status' => 'preparing',
                'fulfilment_type' => 'delivery',
                'customer_name' => 'David Sterling',
                'customer_email' => 'david.sterling@example.co.uk',
                'customer_phone' => '+44 7911 123456',
                'delivery_address' => [
                    'street' => '24 Peckham Rye',
                    'city' => 'London',
                    'postal_code' => 'SE15 4JR',
                    'country' => 'United Kingdom',
                ],
                'subtotal_minor' => 8500, // £85.00
                'delivery_fee_minor' => 2500, // £25.00 express air cargo
                'discount_minor' => 0,
                'tax_minor' => 0,
                'total_minor' => 11000, // £110.00
                'currency' => 'GBP',
                'notes' => 'Vacuum-sealed air freight for Saturday delivery.',
            ]
        );

        if ($order2->wasRecentlyCreated || $order2->items()->count() === 0) {
            OrderItem::create([
                'order_id' => $order2->id,
                'menu_item_id' => $egusi?->id,
                'name' => 'Egusi Soup with Pounded Yam (Chilled 2kg Tub)',
                'quantity' => 1,
                'unit_price_minor' => 5500,
                'total_minor' => 5500,
            ]);

            OrderItem::create([
                'order_id' => $order2->id,
                'menu_item_id' => $jollof?->id,
                'name' => 'Smoky Party Jollof (Chilled 1.5kg Tub)',
                'quantity' => 1,
                'unit_price_minor' => 3000,
                'total_minor' => 3000,
            ]);

            Payment::create([
                'order_id' => $order2->id,
                'gateway' => 'stripe',
                'reference' => 'pi_stripe_' . uniqid(),
                'status' => 'success',
                'amount_minor' => 11000,
                'currency' => 'GBP',
                'paid_at' => now()->subHours(1),
            ]);

            $invoiceService->createFromOrder($order2);
        }

        // 3. Order 3: Out for Delivery (NGN, Bank Transfer)
        $order3 = Order::updateOrCreate(
            ['order_number' => 'NK-2026-00103'],
            [
                'user_id' => null,
                'delivery_zone_id' => $mainlandZone?->id,
                'status' => 'out_for_delivery',
                'fulfilment_type' => 'delivery',
                'customer_name' => 'Chidi Anyanwu',
                'customer_email' => 'chidi.a@example.com',
                'customer_phone' => '+234 802 987 6543',
                'delivery_address' => [
                    'street' => '18 Isaac John Street, GRA',
                    'city' => 'Ikeja',
                    'state' => 'Lagos',
                    'country' => 'Nigeria',
                ],
                'subtotal_minor' => 700000,
                'delivery_fee_minor' => 400000,
                'discount_minor' => 0,
                'tax_minor' => 0,
                'total_minor' => 1100000,
                'currency' => 'NGN',
            ]
        );

        if ($order3->wasRecentlyCreated || $order3->items()->count() === 0) {
            OrderItem::create([
                'order_id' => $order3->id,
                'menu_item_id' => $suya?->id,
                'name' => 'Flame-Grilled Beef Suya Platter',
                'quantity' => 2,
                'unit_price_minor' => 350000,
                'total_minor' => 700000,
            ]);

            Payment::create([
                'order_id' => $order3->id,
                'gateway' => 'bank_transfer',
                'reference' => 'TRF-' . strtoupper(uniqid()),
                'status' => 'success',
                'amount_minor' => 1100000,
                'currency' => 'NGN',
                'paid_at' => now()->subHours(2),
            ]);

            $invoiceService->createFromOrder($order3);
        }

        // 4. Catering Requests
        $grandBuffet = CateringPackage::where('slug', 'owambe-grand-wedding-gala-buffet')->first();
        $corporateLunch = CateringPackage::where('slug', 'executive-corporate-luncheon')->first();

        CateringRequest::updateOrCreate(
            ['reference' => 'CAT-DEMO-001'],
            [
                'catering_package_id' => $grandBuffet?->id,
                'customer_name' => 'Otunba Adeleke & Co.',
                'customer_email' => 'adeleke.events@example.com',
                'customer_phone' => '+234 803 555 0199',
                'guest_count' => 150,
                'event_date' => now()->addDays(20)->toDateString(),
                'venue' => 'Landmark Event Centre, Victoria Island, Lagos',
                'dietary_notes' => '15 Halal and 10 Pescatarian guest options required. Live Suya Grilling Station on terrace.',
                'status' => 'quote_sent',
                'quoted_total_minor' => 270000000, // ₦2,700,000
                'deposit_minor' => 135000000, // 50% = ₦1,350,000
                'currency' => 'NGN',
                'admin_notes' => 'Quoted ₦18,000 per guest inclusive of live grill station and full staff.',
            ]
        );

        CateringRequest::updateOrCreate(
            ['reference' => 'CAT-DEMO-002'],
            [
                'catering_package_id' => $corporateLunch?->id,
                'customer_name' => 'Fintech Innovators Lagos',
                'customer_email' => 'hr@fintechinnovators.test',
                'customer_phone' => '+234 809 111 2233',
                'guest_count' => 35,
                'event_date' => now()->addDays(7)->toDateString(),
                'venue' => 'Floor 14, Heritage Towers, Marina, Lagos',
                'dietary_notes' => 'Prompt 12:30 PM delivery. Individual eco-friendly executive bento boxes.',
                'status' => 'deposit_paid',
                'quoted_total_minor' => 42000000, // ₦420,000
                'deposit_minor' => 21000000, // ₦210,000
                'currency' => 'NGN',
                'admin_notes' => '50% deposit paid via Bank Transfer. Prep scheduled for 10:00 AM.',
            ]
        );

        CateringRequest::updateOrCreate(
            ['reference' => 'CAT-DEMO-003'],
            [
                'catering_package_id' => null,
                'customer_name' => 'Mrs. Ngozi Eke',
                'customer_email' => 'ngozi.eke@example.com',
                'customer_phone' => '+234 818 444 8899',
                'guest_count' => 50,
                'event_date' => now()->addDays(14)->toDateString(),
                'venue' => 'Private Residence, Ikeja GRA',
                'dietary_notes' => 'Traditional 50th Birthday Party. Need large soup coolers and party jollof.',
                'status' => 'submitted',
                'currency' => 'NGN',
            ]
        );

        // 5. Special Requests (Off-menu dishes)
        SpecialRequest::updateOrCreate(
            ['reference' => 'REQ-DEMO-001'],
            [
                'customer_name' => 'Barrister Tunde Phillips',
                'customer_email' => 'tunde.phillips@example.com',
                'customer_phone' => '+234 802 333 4455',
                'description' => 'Authentic Calabar Native Fisherman Soup (5L party cooler) cooked with live periwinkles, giant river prawns, fresh ngolo clams, and sea catfish. Medium spice.',
                'quantity' => 1,
                'needed_by' => now()->addDays(4)->toDateString(),
                'budget_minor' => 8000000, // ₦80,000
                'status' => 'quote_sent',
                'quoted_total_minor' => 7500000, // ₦75,000
                'currency' => 'NGN',
                'admin_notes' => 'Chef reviewed fresh seafood market rates. Quoted ₦75,000 inclusive of cooler packaging.',
            ]
        );

        SpecialRequest::updateOrCreate(
            ['reference' => 'REQ-DEMO-002'],
            [
                'customer_name' => 'Amaka Nwosu',
                'customer_email' => 'amaka.nwosu@example.com',
                'customer_phone' => '+234 703 667 8899',
                'description' => 'Abacha and Ugba (African Salad) party platter for 12 guests with grilled point-and-kill fish, garden eggs, and utazi leaves.',
                'quantity' => 1,
                'needed_by' => now()->addDays(6)->toDateString(),
                'budget_minor' => 3500000,
                'status' => 'accepted',
                'quoted_total_minor' => 3000000,
                'currency' => 'NGN',
                'admin_notes' => 'Quote accepted. Cooking scheduled.',
            ]
        );
    }
}

