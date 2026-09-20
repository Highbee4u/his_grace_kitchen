<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::create([
            'key' => 'site_name',
            'value' => 'Nigerian Kitchen',
        ]);

        SiteSetting::create([
            'key' => 'faqs',
            'value' => [
                [
                    'question' => 'Do you ship to London and the US?',
                    'answer' => 'Yes, our chilled courier service ships to London, UK and major North American cities.',
                ],
            ],
        ]);
    }

    public function test_home_page_renders_seo_tags_and_json_ld(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta property="og:image"', false);
        $response->assertSee('<meta name="twitter:card"', false);
        $response->assertSee('"@type": "Restaurant"', false);
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('Do you ship to London and the US?');
    }

    public function test_dish_page_renders_menu_item_and_breadcrumb_json_ld(): void
    {
        $category = MenuCategory::create([
            'name' => 'Classic Rice Dishes',
            'slug' => 'classic-rice-dishes',
            'is_active' => true,
        ]);

        $item = MenuItem::create([
            'menu_category_id' => $category->id,
            'name' => 'Signature Party Jollof',
            'slug' => 'signature-party-jollof',
            'price_minor' => 450000,
            'is_available' => true,
            'description' => 'Smoky wood-fired authentic Nigerian party jollof.',
        ]);

        $response = $this->get(route('menu.show', $item->slug));

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('"@type": "MenuItem"', false);
        $response->assertSee('"@type": "Offer"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('4500.00');
        $response->assertSee('Signature Party Jollof');
    }

    public function test_sitemap_command_and_route_generate_valid_xml(): void
    {
        $this->artisan('sitemap:generate')
            ->assertSuccessful();

        $this->assertFileExists(public_path('sitemap.xml'));

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
    }

    public function test_robots_txt_contains_sitemap_and_disallows_admin(): void
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /admin/', $robotsContent);
        $this->assertStringContainsString('Sitemap:', $robotsContent);
    }

    public function test_catering_honeypot_blocks_bot_submissions(): void
    {
        $response = $this->post(route('catering.store'), [
            'customer_name' => 'Spam Bot',
            'customer_email' => 'bot@spam.com',
            'customer_phone' => '1234567890',
            'guest_count' => 50,
            'event_date' => now()->addMonth()->format('Y-m-d'),
            'venue' => 'Lagos',
            'website_hp' => 'http://spam-link.com', // honeypot filled
        ]);

        $response->assertSessionHas('error', 'Spam submission detected.');
    }
}
