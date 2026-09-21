@extends('layouts.storefront')

@section('title', 'Customer Dashboard | His Grace Kitchen LTD')
@section('meta_description', 'Manage your food orders, track active deliveries, review event catering quotes, and view your dining history.')

@section('content')
<div class="bg-[#FCFBF7] min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Customer Header Banner -->
        <div class="relative overflow-hidden bg-gradient-to-r from-[#0D4A2B] via-[#125834] to-[#0A3820] rounded-3xl p-6 sm:p-8 text-white shadow-xl">
            <!-- Decorative Background Pattern -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute right-32 -top-12 w-48 h-48 rounded-full bg-amber-400/10 pointer-events-none blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <!-- User Info -->
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-amber-400 text-stone-950 font-serif font-black text-2xl sm:text-3xl flex items-center justify-center shadow-lg border-2 border-white/20 shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight">
                                Welcome, {{ $user->name }}
                            </h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/30">
                                Loyal Diner
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-stone-200 mt-1">
                            {{ $user->email }} • Member since {{ $user->created_at->format('F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Profile Actions -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <a 
                        href="{{ route('profile.edit') }}" 
                        class="inline-flex items-center justify-center gap-1.5 bg-white/10 hover:bg-white/20 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all border border-white/15 backdrop-blur-xs flex-1 md:flex-initial"
                    >
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="flex-1 md:flex-initial">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full inline-flex items-center justify-center gap-1.5 bg-red-600/80 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stat Counter Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Total Orders -->
            <div class="bg-white p-5 sm:p-6 rounded-3xl border border-stone-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-stone-500 block">Total Orders</span>
                    <span class="font-serif text-2xl sm:text-3xl font-extrabold text-stone-900 mt-1 block">
                        {{ $orders->count() }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>

            <!-- Active In-Progress Orders -->
            <div class="bg-white p-5 sm:p-6 rounded-3xl border border-stone-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-stone-500 block">Active Deliveries</span>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-serif text-2xl sm:text-3xl font-extrabold text-stone-900">
                            {{ $activeOrders->count() }}
                        </span>
                        @if ($activeOrders->count() > 0)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                Live
                            </span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#0D4A2B] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>

            <!-- Catering & Quotes -->
            <div class="bg-white p-5 sm:p-6 rounded-3xl border border-stone-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-stone-500 block">Event & Quotes</span>
                    <span class="font-serif text-2xl sm:text-3xl font-extrabold text-stone-900 mt-1 block">
                        {{ $cateringRequests->count() + $specialRequests->count() }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-800 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/>
                    </svg>
                </div>
            </div>

            <!-- Reviews Contributed -->
            <div class="bg-white p-5 sm:p-6 rounded-3xl border border-stone-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-stone-500 block">Reviews Given</span>
                    <span class="font-serif text-2xl sm:text-3xl font-extrabold text-stone-900 mt-1 block">
                        {{ $reviews->count() }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Live In-Progress Orders Alert (if any active order exists) -->
        @if ($activeOrders->isNotEmpty())
            @php $latestActive = $activeOrders->first(); @endphp
            <div class="bg-emerald-50 border-2 border-emerald-300 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">Active Kitchen Order</span>
                            <span class="bg-emerald-200 text-emerald-900 font-extrabold text-[10px] uppercase px-2 py-0.5 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $latestActive->status)) }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-stone-900 mt-0.5">
                            Order #{{ $latestActive->order_number }} is currently being prepped or dispatched fresh to you!
                        </p>
                    </div>
                </div>

                <a 
                    href="{{ route('orders.track', ['order_number' => $latestActive->order_number]) }}" 
                    class="inline-flex items-center gap-2 bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-sm transition-colors whitespace-nowrap shrink-0 w-full sm:w-auto justify-center"
                >
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Live Track Order
                </a>
            </div>
        @endif

        <!-- Quick Kitchen Shortcuts -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <a href="{{ route('menu.index') }}" class="bg-white hover:bg-emerald-50/50 p-4 rounded-2xl border border-stone-200 text-center transition-all group shadow-xs">
                <span class="text-2xl block mb-1">🍲</span>
                <span class="text-xs font-bold text-stone-800 group-hover:text-[#0D4A2B] block">Order Fresh Food</span>
                <span class="text-[10px] text-stone-500">Browse Full Menu</span>
                <span class="block text-[#0D4A2B] text-xs mt-1 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>
            <a href="{{ route('combos.index') }}" class="bg-white hover:bg-emerald-50/50 p-4 rounded-2xl border border-stone-200 text-center transition-all group shadow-xs">
                <span class="text-2xl block mb-1">🍱</span>
                <span class="text-xs font-bold text-stone-800 group-hover:text-[#0D4A2B] block">Combos & Deals</span>
                <span class="text-[10px] text-stone-500">Feast Bundles</span>
                <span class="block text-[#0D4A2B] text-xs mt-1 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>
            <a href="{{ route('catering.index') }}" class="bg-white hover:bg-emerald-50/50 p-4 rounded-2xl border border-stone-200 text-center transition-all group shadow-xs">
                <span class="text-2xl block mb-1">🎉</span>
                <span class="text-xs font-bold text-stone-800 group-hover:text-[#0D4A2B] block">Event Catering</span>
                <span class="text-[10px] text-stone-500">Weddings & Galas</span>
                <span class="block text-[#0D4A2B] text-xs mt-1 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>
            <a href="{{ route('special-requests.create') }}" class="bg-white hover:bg-emerald-50/50 p-4 rounded-2xl border border-stone-200 text-center transition-all group shadow-xs">
                <span class="text-2xl block mb-1">✨</span>
                <span class="text-xs font-bold text-stone-800 group-hover:text-[#0D4A2B] block">Custom Dish</span>
                <span class="text-[10px] text-stone-500">Off-Menu Requests</span>
                <span class="block text-[#0D4A2B] text-xs mt-1 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
            </a>
        </div>

        <!-- Food Orders Section -->
        <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-stone-900">
                        My Food Orders
                    </h2>
                    <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                        Track live status, download invoices, or leave meal reviews.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('orders.track') }}" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-900 bg-stone-100 hover:bg-stone-200 px-3.5 py-2 rounded-xl transition-colors"
                    >
                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Lookup by Order #
                    </a>
                    <a 
                        href="{{ route('menu.index') }}" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-[#0D4A2B] hover:bg-[#09351e] px-4 py-2 rounded-xl transition-colors shadow-xs"
                    >
                        New Order
                    </a>
                </div>
            </div>

            @if ($orders->isEmpty())
                <!-- Empty Orders State -->
                <div class="py-16 px-4 text-center max-w-sm mx-auto space-y-4">
                    <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center mx-auto text-2xl">
                        🍛
                    </div>
                    <h3 class="font-serif text-lg font-bold text-stone-900">No Orders Placed Yet</h3>
                    <p class="text-xs sm:text-sm text-stone-500">
                        Craving smoky firewood party jollof, slow-simmered rich egusi, or succulent charcoal suya? Treat yourself today!
                    </p>
                    <a 
                        href="{{ route('menu.index') }}" 
                        class="inline-block bg-[#0D4A2B] text-white text-xs font-bold px-6 py-2.5 rounded-full shadow-sm hover:bg-[#09351e] transition-colors"
                    >
                        Explore Our Menu
                    </a>
                </div>
            @else
                <!-- Responsive Orders Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-stone-50/80 text-stone-600 text-[11px] uppercase tracking-wider font-bold border-b border-stone-100">
                            <tr>
                                <th class="py-3.5 px-4 sm:px-6">Order</th>
                                <th class="py-3.5 px-4">Date</th>
                                <th class="py-3.5 px-4">Fulfilment</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Total</th>
                                <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-stone-700">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-stone-50/50 transition-colors">
                                    <!-- Order Info & Items summary -->
                                    <td class="py-4 px-4 sm:px-6">
                                        <a href="{{ route('orders.show', $order->order_number) }}" class="font-bold text-[#0D4A2B] hover:underline block">
                                            #{{ $order->order_number }}
                                        </a>
                                        <span class="text-[11px] text-stone-500 line-clamp-1 mt-0.5" title="{{ $order->items->pluck('name')->join(', ') }}">
                                            {{ $order->items->pluck('name')->join(', ') ?: 'Standard Feast Items' }}
                                        </span>
                                    </td>

                                    <!-- Date -->
                                    <td class="py-4 px-4 whitespace-nowrap text-stone-500 text-xs">
                                        {{ $order->created_at->format('M d, Y') }}
                                        <span class="block text-[10px] text-stone-400">{{ $order->created_at->format('h:i A') }}</span>
                                    </td>

                                    <!-- Fulfilment -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if ($order->fulfilment_type === 'pickup')
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full">
                                                🏪 Kitchen Pickup
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                                                🚴 Express Delivery
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status Pill -->
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-amber-100 text-amber-800',
                                                'pending_payment' => 'bg-amber-100 text-amber-800',
                                                'paid' => 'bg-blue-100 text-blue-800',
                                                'confirmed' => 'bg-sky-100 text-sky-800',
                                                'preparing' => 'bg-purple-100 text-purple-800',
                                                'ready' => 'bg-teal-100 text-teal-800',
                                                'on_delivery' => 'bg-indigo-100 text-indigo-800',
                                                'out_for_delivery' => 'bg-indigo-100 text-indigo-800',
                                                'delivered' => 'bg-emerald-100 text-emerald-800',
                                                'completed' => 'bg-emerald-100 text-emerald-800',
                                                'cancelled' => 'bg-rose-100 text-rose-800',
                                            ];
                                            $badgeClass = $statusColors[$order->status] ?? 'bg-stone-100 text-stone-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide {{ $badgeClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>

                                    <!-- Price in customer active currency -->
                                    <td class="py-4 px-4 whitespace-nowrap font-bold text-stone-900">
                                        <span x-text="$store.currency.format({{ $order->total_minor }})">
                                            ₦{{ number_format($order->total_minor / 100, 2) }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-4 sm:px-6 whitespace-nowrap text-right space-x-1 sm:space-x-2">
                                        <!-- Track Button -->
                                        <a 
                                            href="{{ route('orders.track', ['order_number' => $order->order_number]) }}" 
                                            class="inline-flex items-center gap-1 text-[11px] font-bold text-[#0D4A2B] bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition-colors"
                                            title="Live Track"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Track
                                        </a>

                                        <!-- Invoice PDF -->
                                        <a 
                                            href="{{ route('orders.invoice', $order->order_number) }}" 
                                            class="inline-flex items-center gap-1 text-[11px] font-bold text-stone-600 bg-stone-100 hover:bg-stone-200 px-2.5 py-1.5 rounded-lg transition-colors"
                                            title="Download Invoice"
                                        >
                                            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            PDF
                                        </a>

                                        <!-- Review Meal -->
                                        @if ($order->status === 'delivered' || $order->status === 'completed')
                                            <a 
                                                href="{{ route('reviews.index') }}#write-review" 
                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg transition-colors"
                                                title="Leave Review"
                                            >
                                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                Review
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Event Catering & Custom Requests Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Event Catering Requests -->
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-serif text-xl font-bold text-stone-900">Event Catering Quotes</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Weddings, birthdays, corporate, and diaspora packs.</p>
                    </div>
                    <a href="{{ route('catering.index') }}" class="text-xs font-bold text-[#0D4A2B] hover:underline">
                        Book Event
                    </a>
                </div>

                @if ($cateringRequests->isEmpty())
                    <div class="text-center py-10 text-stone-500 space-y-2">
                        <span class="text-2xl block">🎪</span>
                        <p class="text-xs">No catering bookings submitted yet.</p>
                        <a href="{{ route('catering.index') }}" class="inline-block text-xs font-bold text-[#0D4A2B] underline">
                            Plan an event with us
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($cateringRequests as $req)
                            <div class="p-4 rounded-2xl border border-stone-200 hover:border-amber-400 transition-colors bg-stone-50/50 flex items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-stone-900">#{{ $req->reference }}</span>
                                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-stone-200 text-stone-700">
                                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-stone-600 mt-1">
                                        {{ $req->package?->name ?? 'Custom Package' }} • {{ $req->guest_count }} Guests
                                    </p>
                                    <span class="text-[10px] text-stone-400 block mt-0.5">
                                        Event Date: {{ \Carbon\Carbon::parse($req->event_date)->format('M d, Y') }}
                                    </span>
                                </div>

                                <a 
                                    href="{{ route('catering.show', $req->reference) }}" 
                                    class="inline-flex items-center gap-1 text-xs font-bold text-white bg-[#0D4A2B] hover:bg-[#09351e] px-3.5 py-1.5 rounded-xl transition-colors shrink-0"
                                >
                                    View Quote
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Custom Special Requests -->
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-serif text-xl font-bold text-stone-900">Special Dish Requests</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Custom off-menu delicacies, soups & dietary adaptations.</p>
                    </div>
                    <a href="{{ route('special-requests.create') }}" class="text-xs font-bold text-[#0D4A2B] hover:underline">
                        Request Dish
                    </a>
                </div>

                @if ($specialRequests->isEmpty())
                    <div class="text-center py-10 text-stone-500 space-y-2">
                        <span class="text-2xl block">👨‍🍳</span>
                        <p class="text-xs">No off-menu requests submitted yet.</p>
                        <a href="{{ route('special-requests.create') }}" class="inline-block text-xs font-bold text-[#0D4A2B] underline">
                            Ask our head chef for any dish
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($specialRequests as $spec)
                            <div class="p-4 rounded-2xl border border-stone-200 hover:border-amber-400 transition-colors bg-stone-50/50 flex items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-stone-900">#{{ $spec->reference }}</span>
                                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-stone-200 text-stone-700">
                                            {{ ucfirst(str_replace('_', ' ', $spec->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-stone-600 mt-1 font-semibold line-clamp-1">
                                        {{ $spec->description }}
                                    </p>
                                    <span class="text-[10px] text-stone-400 block mt-0.5">
                                        Needed by: {{ $spec->needed_by ? \Carbon\Carbon::parse($spec->needed_by)->format('M d, Y') : 'As soon as possible' }} • Qty: {{ $spec->quantity }}
                                    </span>
                                </div>

                                <a 
                                    href="{{ route('special-requests.show', $spec->reference) }}" 
                                    class="inline-flex items-center gap-1 text-xs font-bold text-white bg-[#0D4A2B] hover:bg-[#09351e] px-3.5 py-1.5 rounded-xl transition-colors shrink-0"
                                >
                                    Review
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
