<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display the full menu with interactive filters.
     */
    public function index(Request $request): View
    {
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount(['items' => fn ($q) => $q->where('is_available', true)])
            ->get();

        $query = MenuItem::where('is_available', true)->with(['category', 'variants']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->query('category')));
        }

        if ($request->filled('search')) {
            $term = '%'.$request->query('search').'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if ($request->filled('spice')) {
            $query->where('spice_level', $request->query('spice'));
        }

        if ($request->boolean('vegetarian')) {
            $query->where('is_vegetarian', true);
        }

        $items = $query->paginate(12)->withQueryString();

        return view('menu.index', compact('categories', 'items'));
    }

    /**
     * Display single dish details.
     */
    public function show(string $slug): View
    {
        $item = MenuItem::where('slug', $slug)
            ->where('is_available', true)
            ->with(['category', 'variants'])
            ->firstOrFail();

        $addOns = AddOn::where('is_available', true)->get();

        $relatedItems = MenuItem::where('menu_category_id', $item->menu_category_id)
            ->where('id', '!=', $item->id)
            ->where('is_available', true)
            ->with(['category', 'variants'])
            ->take(3)
            ->get();

        $reviews = $item->reviews()
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('menu.show', compact('item', 'addOns', 'relatedItems', 'reviews'));
    }
}
