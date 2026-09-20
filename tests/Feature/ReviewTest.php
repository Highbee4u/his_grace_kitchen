<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\RoleAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

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
            'price_minor' => 450000,
            'is_available' => true,
        ]);
    }

    public function test_reviews_index_page_renders(): void
    {
        Review::create([
            'customer_name' => 'Dr. Folake Adeyemi',
            'customer_location' => 'Lekki Phase 1',
            'rating' => 5,
            'title' => 'Nostalgic Firewood Smoke',
            'comment' => 'The smoky party jollof is the closest thing to my grandmother’s kitchen.',
            'menu_item_id' => $this->jollofItem->id,
            'status' => 'approved',
            'is_featured' => true,
        ]);

        $response = $this->get(route('reviews.index'));

        $response->assertStatus(200)
            ->assertSee('What Food Lovers Say About Us')
            ->assertSee('Dr. Folake Adeyemi')
            ->assertSee('Nostalgic Firewood Smoke')
            ->assertSee('Write a Review');
    }

    public function test_guest_can_submit_review_for_dish(): void
    {
        $payload = [
            'customer_name' => 'Emeka Okafor',
            'customer_email' => 'emeka@example.com',
            'customer_location' => 'Victoria Island, Lagos',
            'rating' => 5,
            'title' => 'Sensational flavors!',
            'comment' => 'The woodsmoke permeating the grains was heavenly and not greasy at all.',
            'menu_item_id' => $this->jollofItem->id,
            'website' => '', // Clean honeypot
        ];

        $response = $this->post(route('reviews.store'), $payload);

        $response->assertSessionHas('review_success');
        $this->assertDatabaseHas('reviews', [
            'customer_name' => 'Emeka Okafor',
            'customer_email' => 'emeka@example.com',
            'rating' => 5,
            'menu_item_id' => $this->jollofItem->id,
            'status' => 'approved',
        ]);
    }

    public function test_customer_can_submit_review_with_order_number_and_gets_verified(): void
    {
        $order = Order::create([
            'order_number' => 'NK-20260920-VERIFY',
            'status' => 'delivered',
            'fulfilment_type' => 'delivery',
            'customer_name' => 'Babatunde',
            'customer_email' => 'babatunde@example.com',
            'customer_phone' => '+2348011112222',
            'subtotal_minor' => 450000,
            'total_minor' => 450000,
            'currency' => 'NGN',
        ]);

        $payload = [
            'customer_name' => 'Babatunde Adeleke',
            'customer_email' => 'babatunde@example.com',
            'rating' => 5,
            'title' => 'Top notch dispatch rider and food',
            'comment' => 'Arrived super warm in the thermal dispatch container.',
            'order_number' => 'NK-20260920-VERIFY',
            'website' => '',
        ];

        $response = $this->post(route('reviews.store'), $payload);

        $response->assertSessionHas('review_success');
        $this->assertDatabaseHas('reviews', [
            'customer_name' => 'Babatunde Adeleke',
            'order_id' => $order->id,
            'is_verified_buyer' => true,
        ]);
    }

    public function test_honeypot_rejects_spam_bot_submission(): void
    {
        $payload = [
            'customer_name' => 'Spam Bot',
            'customer_email' => 'bot@spammer.com',
            'rating' => 5,
            'comment' => 'Cheap pills and loans click here!',
            'website' => 'http://spam-link.ru', // Filled honeypot
        ];

        $response = $this->post(route('reviews.store'), $payload);

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseMissing('reviews', [
            'customer_name' => 'Spam Bot',
        ]);
    }

    public function test_dish_show_page_displays_approved_reviews(): void
    {
        Review::create([
            'customer_name' => 'Yemi Alade',
            'rating' => 5,
            'title' => 'Exceptional Firewood Aroma',
            'comment' => 'Could not stop eating until the plate was clean.',
            'menu_item_id' => $this->jollofItem->id,
            'status' => 'approved',
        ]);

        // Unapproved review should not appear
        Review::create([
            'customer_name' => 'Pending Reviewer',
            'rating' => 2,
            'comment' => 'Pending moderation message.',
            'menu_item_id' => $this->jollofItem->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('menu.show', $this->jollofItem->slug));

        $response->assertStatus(200)
            ->assertSee('Yemi Alade')
            ->assertSee('Exceptional Firewood Aroma')
            ->assertDontSee('Pending moderation message');
    }

    public function test_admin_can_access_review_resource(): void
    {
        $admin = User::where('email', 'admin@africankitchen.test')->first();

        Review::create([
            'customer_name' => 'Review For Admin',
            'rating' => 5,
            'comment' => 'Testing review in admin panel.',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get('/admin/reviews');

        $response->assertStatus(200)
            ->assertSee('Review For Admin');
    }
}
