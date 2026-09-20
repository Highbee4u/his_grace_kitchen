<?php

namespace App\Http\Controllers;

use App\Models\CateringRequest;
use App\Models\Order;
use App\Models\Review;
use App\Models\SpecialRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show customer dashboard or redirect staff to admin panel.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Staff members are redirected directly to Filament Admin
        if ($user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['super_admin', 'manager', 'kitchen_staff'])) {
            return redirect('/admin');
        }

        // Fetch customer orders by user_id or customer_email
        $orders = Order::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
            })
            ->with(['items.menuItem', 'deliveryZone'])
            ->latest()
            ->get();

        // Active orders currently in progress
        $activeOrders = $orders->whereIn('status', [
            'pending', 'pending_payment', 'paid', 'confirmed', 'preparing', 'ready', 'on_delivery', 'out_for_delivery',
        ]);

        // Catering requests
        $cateringRequests = CateringRequest::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
            })
            ->with('package')
            ->latest()
            ->get();

        // Special requests
        $specialRequests = SpecialRequest::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
            })
            ->latest()
            ->get();

        // Reviews submitted by this customer
        $reviews = Review::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('customer_email', $user->email);
            })
            ->with('menuItem')
            ->latest()
            ->get();

        return view('dashboard', [
            'user' => $user,
            'orders' => $orders,
            'activeOrders' => $activeOrders,
            'cateringRequests' => $cateringRequests,
            'specialRequests' => $specialRequests,
            'reviews' => $reviews,
        ]);
    }
}
