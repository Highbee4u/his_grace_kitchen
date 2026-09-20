<?php

namespace Tests\Feature;

use App\Models\CateringPackage;
use App\Models\Combo;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use App\Models\User;
use Database\Seeders\RoleAndSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndSettingsSeeder::class);
    }

    public function test_home_page_renders_successfully(): void
    {
        $category = MenuCategory::create([
            'name' => 'Signature Rice Dishes',
            'slug' => 'signature-rice',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $dish = MenuItem::create([
            'menu_category_id' => $category->id,
            'name' => 'Smoky Party Jollof',
            'slug' => 'smoky-party-jollof',
            'price_minor' => 450000,
            'is_available' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200)
            ->assertSee('Smoky Party Jollof')
            ->assertSee('Signature Rice Dishes');
    }

    public function test_menu_catalog_renders_and_filters_by_category(): void
    {
        $cat1 = MenuCategory::create([
            'name' => 'Rice Dishes',
            'slug' => 'rice-dishes',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $cat2 = MenuCategory::create([
            'name' => 'Soups',
            'slug' => 'soups',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $dish1 = MenuItem::create([
            'menu_category_id' => $cat1->id,
            'name' => 'Firewood Jollof',
            'slug' => 'firewood-jollof',
            'price_minor' => 450000,
            'is_available' => true,
        ]);

        $dish2 = MenuItem::create([
            'menu_category_id' => $cat2->id,
            'name' => 'Authentic Egusi',
            'slug' => 'authentic-egusi',
            'price_minor' => 500000,
            'is_available' => true,
        ]);

        // Unfiltered catalog
        $response = $this->get(route('menu.index'));
        $response->assertStatus(200)
            ->assertSee('Firewood Jollof')
            ->assertSee('Authentic Egusi');

        // Filter by category slug
        $filterResponse = $this->get(route('menu.index', ['category' => 'rice-dishes']));
        $filterResponse->assertStatus(200)
            ->assertSee('Firewood Jollof')
            ->assertDontSee('Authentic Egusi');
    }

    public function test_dish_detail_page_renders_with_variants(): void
    {
        $category = MenuCategory::create([
            'name' => 'Soups & Swallows',
            'slug' => 'soups-swallows',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $dish = MenuItem::create([
            'menu_category_id' => $category->id,
            'name' => 'Special Fisherman Soup',
            'slug' => 'special-fisherman-soup',
            'description' => 'Fresh river prawns, jumbo snails, and fresh fish simmered in spicy broth.',
            'price_minor' => 850000,
            'spice_level' => 3,
            'is_available' => true,
        ]);

        MenuItemVariant::create([
            'menu_item_id' => $dish->id,
            'name' => 'Extra Jumbo Prawns',
            'price_minor' => 250000,
            'is_default' => false,
            'is_available' => true,
        ]);

        $response = $this->get(route('menu.show', $dish->slug));

        $response->assertStatus(200)
            ->assertSee('Special Fisherman Soup')
            ->assertSee('Extra Jumbo Prawns');
    }

    public function test_combos_page_renders_active_combos(): void
    {
        $combo = Combo::create([
            'name' => 'Lagos Owambe Box',
            'slug' => 'lagos-owambe-box',
            'description' => 'Smoky Jollof, Chicken, Dodo, and Zobo',
            'price_minor' => 750000,
            'items' => ['Jollof Rice', 'Fried Chicken', 'Dodo'],
            'is_active' => true,
        ]);

        $response = $this->get(route('combos.index'));

        $response->assertStatus(200)
            ->assertSee('Lagos Owambe Box')
            ->assertSee('Jollof Rice')
            ->assertSee('Fried Chicken');
    }

    public function test_catering_page_renders_and_accepts_quote_request(): void
    {
        $package = CateringPackage::create([
            'name' => 'Wedding Feast',
            'slug' => 'wedding-feast',
            'description' => 'Full buffet spread with live suya',
            'pricing_model' => 'per_head',
            'price_minor' => 1800000,
            'includes' => ['Live Suya Station', 'Soup Bar'],
            'is_active' => true,
        ]);

        $indexResponse = $this->get(route('catering.index'));
        $indexResponse->assertStatus(200)->assertSee('Wedding Feast');

        $formData = [
            'customer_name' => 'Chief Olumide Adeleke',
            'customer_email' => 'olumide@example.com',
            'customer_phone' => '+2348039998888',
            'catering_package_id' => $package->id,
            'guest_count' => 150,
            'event_date' => now()->addDays(14)->format('Y-m-d'),
            'venue' => 'Eko Hotel Grand Ballroom, VI, Lagos',
            'dietary_notes' => '10 vegetarian guests, mild spice on jollof',
        ];

        $postResponse = $this->post(route('catering.store'), $formData);

        $postResponse->assertRedirect(route('catering.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('catering_requests', [
            'customer_name' => 'Chief Olumide Adeleke',
            'customer_email' => 'olumide@example.com',
            'catering_package_id' => $package->id,
            'guest_count' => 150,
            'status' => 'submitted',
        ]);
    }

    public function test_special_request_page_renders_and_accepts_submission(): void
    {
        $createResponse = $this->get(route('special-requests.create'));
        $createResponse->assertStatus(200)->assertSee('Off-Menu Traditional Delicacies');

        $formData = [
            'customer_name' => 'Ngozi Okonjo',
            'customer_email' => 'ngozi@example.com',
            'customer_phone' => '+2348021112222',
            'description' => '5-Litre Pot of Authentic Calabar Afang Soup with shelled periwinkles and smoked catfish.',
            'quantity' => 2,
            'needed_by' => now()->addDays(5)->format('Y-m-d'),
            'budget' => 45000,
        ];

        $postResponse = $this->post(route('special-requests.store'), $formData);

        $postResponse->assertRedirect(route('special-requests.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('special_requests', [
            'customer_name' => 'Ngozi Okonjo',
            'quantity' => 2,
            'budget_minor' => 4500000,
            'status' => 'submitted',
        ]);
    }

    public function test_about_and_contact_pages_render_successfully(): void
    {
        $aboutResponse = $this->get(route('about'));
        $aboutResponse->assertStatus(200)->assertSee('The Soul of');

        $contactResponse = $this->get(route('contact'));
        $contactResponse->assertStatus(200)->assertSee('Get in Touch with');
    }

    public function test_admin_panel_access_is_restricted_to_staff_roles(): void
    {
        // Unauthenticated user redirected to login
        $guestResponse = $this->get('/admin');
        $guestResponse->assertRedirect('/admin/login');

        // Regular customer user without staff role is forbidden
        $customer = User::factory()->create();
        $this->actingAs($customer);
        $customerResponse = $this->get('/admin');
        $customerResponse->assertStatus(403);

        // Staff user with super_admin role can access
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');
        $this->actingAs($admin);
        $adminResponse = $this->get('/admin');
        $adminResponse->assertStatus(200);
    }
}
