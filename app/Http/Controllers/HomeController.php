<?php

namespace App\Http\Controllers;

use App\Models\CateringPackage;
use App\Models\Combo;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Review;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the storefront home page.
     */
    public function index(): View
    {
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount(['items' => fn ($q) => $q->where('is_available', true)])
            ->get();

        $signatureDishes = MenuItem::where('is_available', true)
            ->with(['category', 'variants'])
            ->latest()
            ->take(8)
            ->get();

        $combos = Combo::where('is_active', true)
            ->take(3)
            ->get();

        $cateringPackages = CateringPackage::where('is_active', true)
            ->take(3)
            ->get();

        $dbReviews = Review::approved()->featured()->with('menuItem')->latest()->take(12)->get();
        if ($dbReviews->count() < 3) {
            $dbReviews = Review::approved()->with('menuItem')->latest()->take(12)->get();
        }

        $allDishes = MenuItem::where('is_available', true)->orderBy('name')->get();

        $testimonials = SiteSetting::get('testimonials', []);

        $reviewChunks = $dbReviews->isNotEmpty()
            ? $dbReviews->chunk(3)
            : collect($testimonials)->chunk(3);

        $faqs = SiteSetting::get('faqs', []);
        $whatsappNumber = SiteSetting::get('whatsapp_number', '+2348000000000');

        return view('home', compact(
            'categories',
            'signatureDishes',
            'combos',
            'cateringPackages',
            'testimonials',
            'dbReviews',
            'reviewChunks',
            'allDishes',
            'faqs',
            'whatsappNumber'
        ));
    }
}
