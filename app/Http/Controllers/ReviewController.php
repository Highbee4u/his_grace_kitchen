<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display all customer reviews and ratings.
     */
    public function index(): View
    {
        $reviews = Review::approved()
            ->with(['menuItem', 'user'])
            ->latest()
            ->paginate(9);

        $totalCount = Review::approved()->count();
        $averageRating = $totalCount > 0 ? round((float) Review::approved()->avg('rating'), 1) : 5.0;

        $ratingCounts = [
            5 => Review::approved()->where('rating', 5)->count(),
            4 => Review::approved()->where('rating', 4)->count(),
            3 => Review::approved()->where('rating', 3)->count(),
            2 => Review::approved()->where('rating', 2)->count(),
            1 => Review::approved()->where('rating', 1)->count(),
        ];

        $dishes = MenuItem::where('is_available', true)->orderBy('name')->get();

        return view('reviews.index', compact('reviews', 'totalCount', 'averageRating', 'ratingCounts', 'dishes'));
    }

    /**
     * Store a newly submitted customer review.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_location' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
            'menu_item_id' => ['nullable', 'exists:menu_items,id'],
            'dish_name' => ['nullable', 'string', 'max:255'],
            'order_number' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'max:0'], // Anti-spam honeypot
        ], [
            'website.max' => 'Spam submission detected.',
        ]);

        $order = null;
        $isVerifiedBuyer = false;

        if (! empty($validated['order_number'])) {
            $order = Order::where('order_number', trim($validated['order_number']))->first();
            if ($order) {
                $isVerifiedBuyer = true;
            }
        } elseif (auth()->check()) {
            $isVerifiedBuyer = auth()->user()->orders()
                ->whereIn('status', ['paid', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'])
                ->exists();
        }

        $dishName = $validated['dish_name'] ?? null;
        if (empty($dishName) && ! empty($validated['menu_item_id'])) {
            $menuItem = MenuItem::find($validated['menu_item_id']);
            $dishName = $menuItem?->name;
        }

        Review::create([
            'user_id' => auth()->id(),
            'menu_item_id' => $validated['menu_item_id'] ?? null,
            'order_id' => $order?->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? auth()->user()?->email,
            'customer_location' => $validated['customer_location'] ?? null,
            'rating' => (int) $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'dish_name' => $dishName,
            'is_verified_buyer' => $isVerifiedBuyer,
            'status' => 'approved',
            'is_featured' => (int) $validated['rating'] >= 5,
        ]);

        return back()->with('review_success', 'Thank you! Your review has been published. We appreciate your authentic feedback!');
    }
}
