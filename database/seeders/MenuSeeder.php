<?php

namespace Database\Seeders;

use App\Models\AddOn;
use App\Models\CateringPackage;
use App\Models\Combo;
use App\Models\DeliveryZone;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use App\Models\Review;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categories = [
            [
                'name' => 'Rice & Grains',
                'slug' => 'rice-grains',
                'description' => 'Smoky party jollof, savory fried rice, and aromatic ofada delicacies.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Soups & Swallows',
                'slug' => 'soups-swallows',
                'description' => 'Hearty, slow-cooked indigenous soups paired with hot, fluffy swallows.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Grills & Street Food',
                'slug' => 'grills-street-food',
                'description' => 'Open-flame suya, peppery asun, whole grilled fish, and authentic street flavors.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Small Chops & Sides',
                'slug' => 'small-chops-sides',
                'description' => 'Fluffy puff-puff, crispy akara, sweet dodo, and golden Nigerian meat pies.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Beverages & Drinks',
                'slug' => 'beverages-drinks',
                'description' => 'Refreshing house-brewed zobo, classic Chapman, and chilled tropical drinks.',
                'sort_order' => 5,
            ],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = MenuCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // 2. Menu Items
        $items = [
            // Rice
            [
                'menu_category_id' => $catModels['rice-grains']->id,
                'name' => 'Smoky Party Jollof Rice',
                'slug' => 'smoky-party-jollof-rice',
                'description' => 'Authentic woodsmoke-infused long grain rice cooked in a rich tomato, tatashe pepper, and aromatics reduction. Served with sweet fried dodo.',
                'price_minor' => 450000, // ₦4,500
                'currency' => 'NGN',
                'spice_level' => 'Medium',
                'allergens' => ['None'],
                'tags' => ['Popular', 'Signature'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['rice-grains']->id,
                'name' => 'Nigerian Party Fried Rice',
                'slug' => 'nigerian-party-fried-rice',
                'description' => 'Fragrant curry-thyme infused rice stir-fried with sweet corn, carrots, green beans, and seasoned diced liver.',
                'price_minor' => 480000,
                'currency' => 'NGN',
                'spice_level' => 'Mild',
                'allergens' => ['None'],
                'tags' => ['Popular'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['rice-grains']->id,
                'name' => 'Ofada Rice & Designer Ayamase Stew',
                'slug' => 'ofada-rice-ayamase-stew',
                'description' => 'Locally harvested aromatic brown Ofada rice paired with bleached palm oil green habanero sauce packed with diced assorted meats, locust beans (iru), and boiled egg.',
                'price_minor' => 550000,
                'currency' => 'NGN',
                'spice_level' => 'Extra Hot',
                'allergens' => ['Egg'],
                'tags' => ['Popular', 'Spicy', 'Chef Special'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            ],
            // Soups & Swallows
            [
                'menu_category_id' => $catModels['soups-swallows']->id,
                'name' => 'Egusi Soup with Pounded Yam',
                'slug' => 'egusi-soup-pounded-yam',
                'description' => 'Slow-simmered toasted melon seed soup with fluted pumpkin leaves (ugwu), smoked dried fish, and ponmo, served alongside freshly prepared hot pounded yam.',
                'price_minor' => 520000,
                'currency' => 'NGN',
                'spice_level' => 'Medium',
                'allergens' => ['Fish'],
                'tags' => ['Popular', 'Signature'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['soups-swallows']->id,
                'name' => 'Efo Riro Elemi Meje',
                'slug' => 'efo-riro-elemi-meje',
                'description' => 'Rich Yoruba-style spinach pot with bell peppers, smoked catfish, dried prawns, cow skin (ponmo), and tender chunks of beef.',
                'price_minor' => 540000,
                'currency' => 'NGN',
                'spice_level' => 'Hot',
                'allergens' => ['Crustaceans', 'Fish'],
                'tags' => ['Popular', 'Spicy'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['soups-swallows']->id,
                'name' => 'Ogbono Draw Soup',
                'slug' => 'ogbono-draw-soup',
                'description' => 'Rich and silky ground wild bush mango seed soup with bitterleaf, smoked catfish, and tender goat meat.',
                'price_minor' => 500000,
                'currency' => 'NGN',
                'spice_level' => 'Mild',
                'allergens' => ['Fish'],
                'tags' => ['Traditional'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['soups-swallows']->id,
                'name' => 'Oha Soup with Goat Meat',
                'slug' => 'oha-soup-goat-meat',
                'description' => 'Traditional eastern delicacy made with shredded oha leaves, ground achi/cocoyam thickener, ogiri, and tender goat meat.',
                'price_minor' => 560000,
                'currency' => 'NGN',
                'spice_level' => 'Medium',
                'allergens' => ['Fish'],
                'tags' => ['Traditional'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1582878826629-29b7ad1cdc43?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['soups-swallows']->id,
                'name' => 'Delta Banga Soup & Starch',
                'slug' => 'delta-banga-soup-starch',
                'description' => 'Rich aromatic palm nut concentrate soup flavored with banga spices and oburunbebe stick, served with freshly steamed yellow starch or eba.',
                'price_minor' => 580000,
                'currency' => 'NGN',
                'spice_level' => 'Medium',
                'allergens' => ['Fish'],
                'tags' => ['Signature'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['soups-swallows']->id,
                'name' => 'Amala with Abula (Ewedu & Gbegiri)',
                'slug' => 'amala-abula-ewedu-gbegiri',
                'description' => 'Piping hot fluffy brown Amala served with silky green ewedu, velvety yellow bean soup gbegiri, and fiery buka stew with soft goat meat.',
                'price_minor' => 490000,
                'currency' => 'NGN',
                'spice_level' => 'Hot',
                'allergens' => ['None'],
                'tags' => ['Popular', 'Lagos Favorite'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1574484284002-952d92456975?auto=format&fit=crop&w=800&q=80',
            ],
            // Grills & Street Food
            [
                'menu_category_id' => $catModels['grills-street-food']->id,
                'name' => 'Flame-Grilled Beef Suya',
                'slug' => 'flame-grilled-beef-suya',
                'description' => 'Tender thin beef ribbons rubbed in northern Nigerian kuli-kuli and yaji pepper spice mix, charred over coals. Garnished with red onions and tomatoes.',
                'price_minor' => 380000,
                'currency' => 'NGN',
                'spice_level' => 'Hot',
                'allergens' => ['Peanuts'],
                'tags' => ['Popular', 'Spicy', 'Street Food'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['grills-street-food']->id,
                'name' => 'Spicy Asun (Peppered Goat Meat)',
                'slug' => 'spicy-asun-peppered-goat-meat',
                'description' => 'Char-grilled succulent goat meat bites tossed in a sizzling pan with scotch bonnet peppers, bell peppers, and sweet onions.',
                'price_minor' => 420000,
                'currency' => 'NGN',
                'spice_level' => 'Extra Hot',
                'allergens' => ['None'],
                'tags' => ['Popular', 'Spicy'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['grills-street-food']->id,
                'name' => 'Whole Grilled Croaker Fish & Bole',
                'slug' => 'whole-grilled-croaker-fish-bole',
                'description' => 'Whole fresh croaker fish seasoned with spicy pepper sauce and grilled to perfection. Served with roasted sweet plantain (bole) and spicy dipping sauce.',
                'price_minor' => 750000,
                'currency' => 'NGN',
                'spice_level' => 'Hot',
                'allergens' => ['Fish'],
                'tags' => ['Signature', 'Popular'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['grills-street-food']->id,
                'name' => 'Catfish Pepper Soup (Point & Kill)',
                'slug' => 'catfish-pepper-soup',
                'description' => 'Fresh live catfish simmered in an invigorating hot broth infused with uda, uziza seeds, ehuru calabash nutmeg, and fresh scent leaves.',
                'price_minor' => 500000,
                'currency' => 'NGN',
                'spice_level' => 'Extra Hot',
                'allergens' => ['Fish'],
                'tags' => ['Popular', 'Spicy'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1548943487-a2e4e43b4853?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['grills-street-food']->id,
                'name' => 'Spicy Nkwobi',
                'slug' => 'spicy-nkwobi',
                'description' => 'Tender diced cow foot cooked and enveloped in rich spicy palm oil paste, potash, and aromatic ground calabash nutmeg, topped with utazi greens.',
                'price_minor' => 450000,
                'currency' => 'NGN',
                'spice_level' => 'Hot',
                'allergens' => ['None'],
                'tags' => ['Traditional'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['grills-street-food']->id,
                'name' => 'Isi Ewu (Spicy Goat Head Pot)',
                'slug' => 'isi-ewu-spicy-goat-head',
                'description' => 'Traditional eastern delicacy made from seasoned goat head chunks enveloped in a rich, nutty palm oil and ehuru reduction with utazi.',
                'price_minor' => 550000,
                'currency' => 'NGN',
                'spice_level' => 'Hot',
                'allergens' => ['None'],
                'tags' => ['Traditional', 'Chef Special'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
            ],
            // Small Chops & Sides
            [
                'menu_category_id' => $catModels['small-chops-sides']->id,
                'name' => 'Golden Puff-Puff (10 pieces)',
                'slug' => 'golden-puff-puff',
                'description' => 'Classic Nigerian golden fried yeast dough balls with a hint of warm nutmeg, pillowy soft inside and lightly crisp outside.',
                'price_minor' => 150000,
                'currency' => 'NGN',
                'spice_level' => 'None',
                'allergens' => ['Gluten'],
                'tags' => ['Popular', 'Vegetarian'],
                'is_vegetarian' => true,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['small-chops-sides']->id,
                'name' => 'Crispy Akara Fritters',
                'slug' => 'crispy-akara-fritters',
                'description' => 'Freshly whipped black-eyed bean batter folded with habanero peppers and red onions, deep-fried to airy perfection.',
                'price_minor' => 180000,
                'currency' => 'NGN',
                'spice_level' => 'Medium',
                'allergens' => ['None'],
                'tags' => ['Vegetarian', 'Breakfast Favorite'],
                'is_vegetarian' => true,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['small-chops-sides']->id,
                'name' => 'Nigerian Baked Meat Pie',
                'slug' => 'nigerian-baked-meat-pie',
                'description' => 'Rich buttery shortcrust pastry crimped and baked golden, filled with seasoned minced beef, soft potato cubes, and carrots.',
                'price_minor' => 200000,
                'currency' => 'NGN',
                'spice_level' => 'Mild',
                'allergens' => ['Gluten', 'Dairy'],
                'tags' => ['Popular'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['small-chops-sides']->id,
                'name' => 'Steamed Fish Moi Moi',
                'slug' => 'steamed-fish-moi-moi',
                'description' => 'Rich savory bean pudding blended with sweet peppers, onions, flaked smoked fish, and boiled egg, steamed wrapped in banana leaves.',
                'price_minor' => 220000,
                'currency' => 'NGN',
                'spice_level' => 'Medium',
                'allergens' => ['Fish', 'Egg'],
                'tags' => ['Popular'],
                'is_vegetarian' => false,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['small-chops-sides']->id,
                'name' => 'Fried Sweet Plantain (Dodo)',
                'slug' => 'fried-sweet-plantain-dodo',
                'description' => 'Ripe Nigerian plantains sliced into diagonal rounds and deep-fried to a caramelized golden-brown sweetness.',
                'price_minor' => 120000,
                'currency' => 'NGN',
                'spice_level' => 'None',
                'allergens' => ['None'],
                'tags' => ['Popular', 'Vegetarian'],
                'is_vegetarian' => true,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['small-chops-sides']->id,
                'name' => 'Crunchy Cinnamon Chin Chin Jar',
                'slug' => 'crunchy-chin-chin-jar',
                'description' => 'Handcrafted crispy fried pastry bites infused with nutmeg, condensed milk, and a dusting of cinnamon sugar. 500g jar.',
                'price_minor' => 250000,
                'currency' => 'NGN',
                'spice_level' => 'None',
                'allergens' => ['Gluten', 'Dairy'],
                'tags' => ['Snack'],
                'is_vegetarian' => true,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1599785209707-a456fc1337bb?auto=format&fit=crop&w=800&q=80',
            ],
            // Beverages
            [
                'menu_category_id' => $catModels['beverages-drinks']->id,
                'name' => 'Artisanal Spiced Zobo Drink',
                'slug' => 'artisanal-spiced-zobo-drink',
                'description' => 'Cold-pressed dried sorrel/hibiscus flowers brewed with crushed ginger, cloves, fresh pineapple peel, and sweetened with raw cane sugar.',
                'price_minor' => 150000,
                'currency' => 'NGN',
                'spice_level' => 'Mild',
                'allergens' => ['None'],
                'tags' => ['Popular', 'Refreshing'],
                'is_vegetarian' => true,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'menu_category_id' => $catModels['beverages-drinks']->id,
                'name' => 'Signature Lagos Chapman',
                'slug' => 'signature-lagos-chapman',
                'description' => 'The iconic Nigerian club cocktail made with Fanta, Sprite, a dash of Angostura bitters, grenadine, cucumber ribbons, and fresh citrus slices.',
                'price_minor' => 180000,
                'currency' => 'NGN',
                'spice_level' => 'None',
                'allergens' => ['None'],
                'tags' => ['Popular', 'Refreshing'],
                'is_vegetarian' => true,
                'is_available' => true,
                'image_url' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $createdItems = [];
        foreach ($items as $itemData) {
            $createdItems[$itemData['slug']] = MenuItem::updateOrCreate(
                ['slug' => $itemData['slug']],
                $itemData
            );
        }

        // 3. Item Variants (Protein & Size choices)
        $proteinDishes = [
            'smoky-party-jollof-rice',
            'nigerian-party-fried-rice',
            'egusi-soup-pounded-yam',
            'efo-riro-elemi-meje',
            'ogbono-draw-soup',
            'amala-abula-ewedu-gbegiri',
        ];

        $proteinOptions = [
            ['name' => 'Fried Chicken Lap', 'price_minor' => 0, 'is_default' => true],
            ['name' => 'Tender Goat Meat (Ogufe)', 'price_minor' => 50000, 'is_default' => false],
            ['name' => 'Assorted Meats (Shaki, Ponmo, Beef)', 'price_minor' => 40000, 'is_default' => false],
            ['name' => 'Fried Titus Fish', 'price_minor' => 60000, 'is_default' => false],
            ['name' => 'Roasted Turkey Wing', 'price_minor' => 80000, 'is_default' => false],
        ];

        foreach ($proteinDishes as $slug) {
            if (isset($createdItems[$slug])) {
                foreach ($proteinOptions as $opt) {
                    MenuItemVariant::updateOrCreate(
                        [
                            'menu_item_id' => $createdItems[$slug]->id,
                            'name' => $opt['name'],
                        ],
                        [
                            'price_minor' => $opt['price_minor'],
                            'currency' => 'NGN',
                            'is_default' => $opt['is_default'],
                            'is_available' => true,
                        ]
                    );
                }
            }
        }

        // 4. Add-ons
        $addOns = [
            ['name' => 'Extra Portion of Fried Plantain (Dodo)', 'price_minor' => 80000],
            ['name' => 'Steamed Moi Moi Foil', 'price_minor' => 120000],
            ['name' => 'Hard-Boiled Egg', 'price_minor' => 30000],
            ['name' => 'Extra Bowl of Designer Ayamase Sauce', 'price_minor' => 200000],
            ['name' => 'Extra Wrap of Hot Pounded Yam', 'price_minor' => 70000],
            ['name' => 'Extra Wrap of Brown Amala', 'price_minor' => 60000],
            ['name' => 'Side of Peppered Fried Titus Fish', 'price_minor' => 150000],
        ];

        foreach ($addOns as $addon) {
            AddOn::updateOrCreate(
                ['name' => $addon['name']],
                [
                    'price_minor' => $addon['price_minor'],
                    'currency' => 'NGN',
                    'is_available' => true,
                ]
            );
        }

        // 5. Combos (Bundles)
        $combos = [
            [
                'name' => 'Lagos Owambe Party Box',
                'slug' => 'lagos-owambe-party-box',
                'description' => 'The ultimate Nigerian party in a box: Smoky Party Jollof, Fried Chicken lap, golden Dodo, rich Moi Moi, and a chilled bottle of Spiced Zobo.',
                'price_minor' => 750000, // ₦7,500 (value ₦9,000)
                'currency' => 'NGN',
                'items' => ['Smoky Party Jollof Rice', 'Fried Chicken Lap', 'Fried Sweet Plantain', 'Steamed Moi Moi', 'Spiced Zobo Drink'],
                'customisation_slots' => ['Protein Choice', 'Drink Choice'],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'The Big Swallow & Soup Feast (for 2)',
                'slug' => 'the-big-swallow-soup-feast',
                'description' => 'Generous bowls of rich Egusi Soup and Efo Riro packed with assorted goat meat, stockfish, and ponmo, accompanied by 2 wraps of Pounded Yam and 2 wraps of Amala.',
                'price_minor' => 1250000,
                'currency' => 'NGN',
                'items' => ['Egusi Soup Bowl', 'Efo Riro Bowl', 'Assorted Goat Meat & Ponmo', '2x Pounded Yam', '2x Amala'],
                'customisation_slots' => ['Choice of Swallows'],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Suya & Street Chops Platter',
                'slug' => 'suya-street-chops-platter',
                'description' => 'Generous platter of tender Beef Suya skewers, fiery peppered Asun, 10 golden Puff-Puffs, fried Dodo, and 2 bottles of iced Chapman.',
                'price_minor' => 1100000,
                'currency' => 'NGN',
                'items' => ['Flame-Grilled Beef Suya', 'Spicy Asun', 'Puff-Puff (10 pcs)', 'Fried Plantains', '2x Signature Chapman'],
                'customisation_slots' => ['Pepper Level (Medium/Hot/Crazy)'],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Lagos Office Executive Lunch Pack',
                'slug' => 'lagos-office-executive-lunch-pack',
                'description' => 'Half-and-half Smoky Jollof and Party Fried Rice, tender Grilled Chicken, Fried Plantains, fresh garden salad, and refreshing Chapman.',
                'price_minor' => 680000,
                'currency' => 'NGN',
                'items' => ['Jollof & Fried Rice Combo', 'Grilled Chicken', 'Dodo', 'Garden Salad', 'Cold Chapman'],
                'customisation_slots' => ['Drink Choice'],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Ofada King Feast',
                'slug' => 'ofada-king-feast',
                'description' => 'Traditional leaf-wrapped Ofada rice with double portion of Ayamase green habanero sauce, 2 boiled eggs, diced goat meat, and fried titus fish.',
                'price_minor' => 850000,
                'currency' => 'NGN',
                'items' => ['Leaf-wrapped Ofada Rice', 'Double Ayamase Stew', '2x Boiled Eggs', 'Diced Goat Meat', 'Fried Titus Fish'],
                'customisation_slots' => ['Spice Level'],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($combos as $combo) {
            Combo::updateOrCreate(['slug' => $combo['slug']], $combo);
        }

        // 6. Catering Packages
        $catering = [
            [
                'name' => 'Owambe Grand Wedding & Gala Buffet',
                'slug' => 'owambe-grand-wedding-gala-buffet',
                'description' => 'Full-service luxury Nigerian catering experience for weddings, milestone anniversaries, and grand galas. Includes live suya BBQ station, full buffet spread, uniformed service staff, and decorative chafing dishes.',
                'pricing_model' => 'per_head',
                'price_minor' => 1800000, // ₦18,000 per guest
                'currency' => 'NGN',
                'includes' => [
                    'Live Flame-Grilled Suya & Asun Grilling Station',
                    'Party Jollof Rice, Fried Rice, and Ofada Rice with Ayamase',
                    'Full Soup Bar: Egusi, Efo Riro, Ogbono with Pounded Yam and Amala',
                    'Small Chops Bar: Puff-Puff, Samosa, Spring Rolls, Peppered Gizzards',
                    'Unlimited House-brewed Zobo and Signature Chapman Mocktails',
                    'Uniformed Professional Servers and Executive Chafing Sets',
                ],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Executive Corporate Luncheon',
                'slug' => 'executive-corporate-luncheon',
                'description' => 'Punctual, premium hot boxed lunch catering or boardroom buffet tailored for executive board meetings, corporate training, and conferences.',
                'pricing_model' => 'per_head',
                'price_minor' => 1200000, // ₦12,000 per guest
                'currency' => 'NGN',
                'includes' => [
                    'Individual Executive Hot Bento Boxes with insulated packaging',
                    'Entree Choice: Smoky Jollof or Fried Rice with Grilled Chicken or Titus Fish',
                    'Side Salad, Sweet Plantains, and Steamed Moi Moi',
                    'Chilled artisanal bottled beverage',
                    'Full biodegradable cutlery and napkin kit',
                ],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Intimate Family Gathering & Home Party',
                'slug' => 'intimate-family-gathering',
                'description' => 'Generous family-style trays delivered hot and ready-to-serve for birthdays, housewarmings, naming ceremonies, and holiday feasts (15 - 50 guests).',
                'pricing_model' => 'per_head',
                'price_minor' => 950000, // ₦9,500 per guest
                'currency' => 'NGN',
                'includes' => [
                    'Large family chaffing trays of Smoky Jollof and Fried Rice',
                    'Trays of Peppered Beef, Spicy Asun, and Crispy Fried Chicken',
                    'Choice of large bowl soup (Egusi or Efo Riro) with wrapped swallows',
                    'Party platter of 50 golden Puff-Puffs and Dodo',
                    '5L Jug of Chilled Spiced Zobo',
                ],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Diaspora Chilled Express Banquet',
                'slug' => 'diaspora-chilled-express-banquet',
                'description' => 'Vacuum-sealed, blast-chilled authentic Nigerian dishes packaged in thermal containers for international express courier to the UK, US, Canada, and Europe.',
                'pricing_model' => 'fixed_package',
                'price_minor' => 15000000, // ₦150,000 fixed package
                'currency' => 'NGN',
                'includes' => [
                    '5x 1kg vacuum sealed tubs of authentic Nigerian soups (Egusi, Ogbono, Efo Riro, Banga)',
                    '3x 1kg containers of Smoky Wood-fired Party Jollof',
                    '1kg pack of vacuum-sealed spicy grilled Asun',
                    'Thermal dry-ice insulated packaging with food safety seal',
                    'Express air cargo dispatch with international tracking',
                ],
                'is_active' => true,
                'image_url' => 'https://images.unsplash.com/photo-1505576399279-565b52d4ac71?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($catering as $package) {
            CateringPackage::updateOrCreate(['slug' => $package['slug']], $package);
        }

        // 7. Delivery Zones
        $zones = [
            [
                'name' => 'Lagos Island Express (Ikoyi, VI, Lekki Phase 1)',
                'areas' => ['Ikoyi', 'Victoria Island', 'Lekki Phase 1', 'Oniru', 'Banana Island'],
                'fee_minor' => 350000,
                'currency' => 'NGN',
                'lead_time_minutes' => 45,
                'allow_pay_on_delivery' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Lagos Mainland Central (Ikeja, Surulere, Yaba)',
                'areas' => ['Ikeja', 'GRA Ikeja', 'Surulere', 'Yaba', 'Maryland', 'Gbagada'],
                'fee_minor' => 400000,
                'currency' => 'NGN',
                'lead_time_minutes' => 60,
                'allow_pay_on_delivery' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Lagos Metro Greater Area (Ajah, Festac, Magodo)',
                'areas' => ['Ajah', 'Sangotedo', 'Festac Town', 'Magodo', 'Ogba', 'Agege'],
                'fee_minor' => 600000,
                'currency' => 'NGN',
                'lead_time_minutes' => 90,
                'allow_pay_on_delivery' => false,
                'is_active' => true,
            ],
            [
                'name' => 'UK Express Courier (London & UK-wide)',
                'areas' => ['Greater London', 'Manchester', 'Birmingham', 'UK Nationwide'],
                'fee_minor' => 4500000, // ₦45,000 / ~£25
                'currency' => 'NGN',
                'lead_time_minutes' => 2880, // 48 hrs
                'allow_pay_on_delivery' => false,
                'is_active' => true,
            ],
            [
                'name' => 'North America Express (US & Canada)',
                'areas' => ['Houston', 'Atlanta', 'New York', 'Toronto', 'US/CA Major Metros'],
                'fee_minor' => 6500000, // ₦65,000 / ~$40
                'lead_time_minutes' => 4320, // 72 hrs
                'allow_pay_on_delivery' => false,
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zone) {
            DeliveryZone::updateOrCreate(['name' => $zone['name']], $zone);
        }

        // 8. Testimonials and CMS site settings
        SiteSetting::updateOrCreate(
            ['key' => 'testimonials'],
            [
                'type' => 'json',
                'value' => [
                    [
                        'name' => 'Dr. Folake Adeyemi',
                        'location' => 'Lekki, Lagos',
                        'comment' => 'The smoky party jollof is the closest thing to my grandmother’s firewood kitchen in Abeokuta. Absolutely divine flavors and always hot on arrival!',
                        'rating' => 5,
                        'dish' => 'Smoky Party Jollof & Dodo',
                    ],
                    [
                        'name' => 'Chinedu Okafor',
                        'location' => 'London, UK',
                        'comment' => 'Received our blast-chilled Egusi and Banga soup in South London in 48 hours. The freshness and authentic scent leaf aroma blew our minds.',
                        'rating' => 5,
                        'dish' => 'Diaspora Express Banquet',
                    ],
                    [
                        'name' => 'Tunde Bakare',
                        'location' => 'Victoria Island',
                        'comment' => 'We used Nigerian Kitchen for our tech firm end-of-year dinner. The live suya station and grilled croaker fish were the highlight of the night!',
                        'rating' => 5,
                        'dish' => 'Owambe Grand Banquet',
                    ],
                ],
            ]
        );

        SiteSetting::updateOrCreate(
            ['key' => 'faqs'],
            [
                'type' => 'json',
                'value' => [
                    [
                        'question' => 'How do you preserve the authentic smoky party jollof flavor?',
                        'answer' => 'We prepare our signature party jollof over cast-iron pots with woodfire techniques, reducing red bell peppers (tatashe), scotch bonnets, and bay leaves until the signature smoke permeates every grain.',
                    ],
                    [
                        'question' => 'How does shipping to the UK, US, and Canada work?',
                        'answer' => 'Our diaspora packages are prepared fresh, blast-chilled, vacuum-sealed in airtight thermal containers with dry ice, and dispatched via priority air cargo to arrive fresh at your doorstep.',
                    ],
                    [
                        'question' => 'How much notice is needed for party catering?',
                        'answer' => 'For event catering packages, we recommend booking at least 3 to 7 days in advance so our head chef can curate ingredients and coordinate staff.',
                    ],
                    [
                        'question' => 'Can I request a special off-menu dish?',
                        'answer' => 'Yes! Use our "Special Requests" tab to tell us what traditional delicacy you crave (e.g. Afang soup, Bitterleaf soup, Fisherman soup, Tuwo Shinkafa). Our kitchen will send you a personalized quote.',
                    ],
                ],
            ]
        );

        // 9. Initial Customer Reviews
        $jollofItem = MenuItem::where('slug', 'smoky-party-jollof-rice')->first();
        $egusiItem = MenuItem::where('slug', 'native-egusi-soup-pounded-yam')->first();
        $suyaItem = MenuItem::where('slug', 'chargrilled-beef-suya-skewers')->first();

        $reviews = [
            [
                'customer_name' => 'Dr. Folake Adeyemi',
                'customer_email' => 'folake.adeyemi@example.com',
                'customer_location' => 'Lekki Phase 1, Lagos',
                'rating' => 5,
                'title' => 'Tastes like authentic Abeokuta firewood cooking!',
                'comment' => 'The smoky party jollof is the closest thing to my grandmother’s firewood kitchen. Absolutely divine flavors, tender peppered beef, and always piping hot on arrival!',
                'menu_item_id' => $jollofItem?->id,
                'dish_name' => 'Smoky Party Jollof & Dodo',
                'is_verified_buyer' => true,
                'status' => 'approved',
                'is_featured' => true,
                'admin_response' => 'Thank you Dr. Folake! We slow-reduce our tatashe base over real seasoned firewood to keep that nostalgic aroma alive.',
            ],
            [
                'customer_name' => 'Chinedu Okafor',
                'customer_email' => 'chinedu.okafor@example.com',
                'customer_location' => 'South London, UK',
                'rating' => 5,
                'title' => 'Arrived fresh in London in 48 hours!',
                'comment' => 'Received our blast-chilled Egusi and Banga soup in South London in 48 hours. The freshness, stockfish tenderness, and authentic scent leaf aroma blew our minds.',
                'menu_item_id' => $egusiItem?->id,
                'dish_name' => 'Native Egusi Soup & Pounded Yam',
                'is_verified_buyer' => true,
                'status' => 'approved',
                'is_featured' => true,
                'admin_response' => 'Thank you Chinedu! Our international blast-chilling air cargo packs are sealed to preserve kitchen freshness across continents.',
            ],
            [
                'customer_name' => 'Tunde Bakare',
                'customer_email' => 'tunde.bakare@example.com',
                'customer_location' => 'Victoria Island, Lagos',
                'rating' => 5,
                'title' => 'Sensational live suya station for our corporate gala',
                'comment' => 'We used Nigerian Kitchen for our tech firm end-of-year dinner. The live suya station, yaji spice blend, and flame-grilled croaker fish were the undisputed highlight of the night!',
                'menu_item_id' => $suyaItem?->id,
                'dish_name' => 'Chargrilled Beef Suya Skewers',
                'is_verified_buyer' => true,
                'status' => 'approved',
                'is_featured' => true,
                'admin_response' => 'A pleasure catering for your team, Tunde! Our grill master loved serving you all.',
            ],
            [
                'customer_name' => 'Amaka Nwosu',
                'customer_email' => 'amaka.nwosu@example.com',
                'customer_location' => 'Ikeja GRA, Lagos',
                'rating' => 5,
                'title' => 'Best party jollof in Lagos hands down',
                'comment' => 'Ordered 3 large trays for my mother’s 60th birthday. Guests thought we hired an on-site owambe caterer. Outstanding spice balance and not overly oily.',
                'menu_item_id' => $jollofItem?->id,
                'dish_name' => 'Smoky Party Jollof Rice',
                'is_verified_buyer' => true,
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Korede Johnson',
                'customer_email' => 'korede.johnson@example.com',
                'customer_location' => 'Manchester, UK',
                'rating' => 4,
                'title' => 'Rich, earthy flavors and great packaging',
                'comment' => 'The native soups thawed wonderfully without losing texture. Would love an option with extra habanero heat next time!',
                'menu_item_id' => $egusiItem?->id,
                'dish_name' => 'Native Egusi Soup',
                'is_verified_buyer' => true,
                'status' => 'approved',
                'is_featured' => false,
            ],
        ];

        foreach ($reviews as $revData) {
            Review::updateOrCreate(
                ['customer_name' => $revData['customer_name'], 'comment' => $revData['comment']],
                $revData
            );
        }
    }
}
