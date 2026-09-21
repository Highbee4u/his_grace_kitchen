@extends('layouts.storefront')

@section('title', 'Full Menu | His Grace Kitchen LTD Authentic Dishes')
@section('meta_description', 'Browse authentic Nigerian dishes: Jollof Rice, Rich Egusi, Pounded Yam, Suya, Grilled Fish, Peppersoup and native snacks with dietary and spice filters.')

@push('json_ld')
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::breadcrumbSchema([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Menu', 'url' => route('menu.index')],
    ])) !!}
@endpush

@section('content')
<div class="bg-gradient-to-r from-[#0D4A2B] via-[#0f5431] to-[#0D4A2B] text-white py-10 sm:py-14 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=1200&q=60');"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="inline-flex items-center gap-1.5 bg-amber-500/20 border border-amber-400/30 text-amber-300 text-[11px] font-extrabold uppercase tracking-widest px-3 py-1 rounded-full">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
            Authentic Native Menu
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white">Explore Our Full Kitchen</h1>
        <p class="text-stone-300 text-sm max-w-xl mx-auto">Every dish is prepared fresh to order using indigenous Nigerian ingredients, authentic spices, and traditional recipes.</p>
    </div>
</div>

<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Filter & Search Controls -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200 border-l-4 border-l-[#0D4A2B] shadow-xs space-y-6">
        <!-- Category Tab Buttons -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <a 
                href="{{ route('menu.index') }}" 
                class="px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ !request('category') ? 'bg-[#0D4A2B] text-white shadow-xs' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}"
            >
                All Categories
            </a>
            @foreach ($categories as $cat)
                <a 
                    href="{{ route('menu.index', array_merge(request()->query(), ['category' => $cat->slug])) }}" 
                    class="px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ request('category') === $cat->slug ? 'bg-[#0D4A2B] text-white shadow-xs' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}"
                >
                    {{ $cat->name }} ({{ $cat->items_count }})
                </a>
            @endforeach
        </div>

        <!-- Search & Secondary Filters Form -->
        <form method="GET" action="{{ route('menu.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
            @if (request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <!-- Search input -->
            <div class="sm:col-span-6 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search dishes (e.g. Jollof, Suya, Egusi, Asun)..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-[#0D4A2B] focus:ring-1 focus:ring-[#0D4A2B]"
                >
                <svg class="w-4 h-4 text-stone-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Spice Filter -->
            <div class="sm:col-span-3">
                <select 
                    name="spice" 
                    class="w-full py-2.5 px-3 rounded-xl border border-stone-300 text-xs font-semibold focus:border-[#0D4A2B] focus:ring-1 focus:ring-[#0D4A2B]"
                    onchange="this.form.submit()"
                >
                    <option value="">Spice Level (All)</option>
                    <option value="None" {{ request('spice') === 'None' ? 'selected' : '' }}>None (Mild & Sweet)</option>
                    <option value="Mild" {{ request('spice') === 'Mild' ? 'selected' : '' }}>Mild 🌶️</option>
                    <option value="Medium" {{ request('spice') === 'Medium' ? 'selected' : '' }}>Medium 🌶️🌶️</option>
                    <option value="Hot" {{ request('spice') === 'Hot' ? 'selected' : '' }}>Hot 🌶️🌶️🌶️</option>
                    <option value="Extra Hot" {{ request('spice') === 'Extra Hot' ? 'selected' : '' }}>Extra Hot 🔥</option>
                </select>
            </div>

            <!-- Vegetarian Checkbox & Submit -->
            <div class="sm:col-span-3 flex items-center justify-between sm:justify-end gap-3">
                <label class="flex items-center gap-2 text-xs font-bold text-stone-700 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="vegetarian" 
                        value="1" 
                        {{ request('vegetarian') ? 'checked' : '' }} 
                        onchange="this.form.submit()"
                        class="rounded border-stone-300 text-[#0D4A2B] focus:ring-[#0D4A2B]"
                    >
                    <span>Vegetarian Only</span>
                </label>

                @if (request()->hasAny(['search', 'spice', 'vegetarian', 'category']))
                    <a href="{{ route('menu.index') }}" class="text-xs text-stone-500 hover:text-red-600 underline">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Dishes Grid -->
    @if ($items->isEmpty())
        <div class="text-center py-20 bg-white rounded-3xl border border-stone-200 space-y-4">
            <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto text-2xl">
                🍲
            </div>
            <h3 class="font-serif text-xl font-bold text-stone-900">No dishes matched your filter</h3>
            <p class="text-stone-500 text-sm max-w-sm mx-auto">Try selecting a different category, clearing search terms, or exploring our custom special request option.</p>
            <a href="{{ route('menu.index') }}" class="inline-block bg-[#0D4A2B] text-white px-6 py-2.5 rounded-full text-xs font-bold hover:bg-[#09351e]">
                View All Dishes
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($items as $dish)
                <div class="flex flex-col bg-white rounded-3xl overflow-hidden border border-stone-200/80 shadow-xs hover:shadow-xl transition-all duration-300 group">
                    <!-- Photo -->
                    <div class="relative aspect-4/3 overflow-hidden bg-stone-100">
                        <img 
                            src="{{ $dish->image }}" 
                            alt="{{ $dish->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                            @if (!empty($dish->tags) && in_array('Popular', (array)$dish->tags))
                                <span class="bg-amber-500 text-stone-950 font-black text-[10px] px-2 py-0.5 rounded-full uppercase">
                                    Popular
                                </span>
                            @endif
                            @if ($dish->spice_level && $dish->spice_level !== 'None')
                                <span class="bg-red-700 text-white font-bold text-[10px] px-2 py-0.5 rounded-full">
                                    {{ $dish->spice_level }} 🌶️
                                </span>
                            @endif
                            @if (!empty($dish->is_vegetarian))
                                <span class="bg-emerald-600 text-white font-bold text-[10px] px-2 py-0.5 rounded-full">
                                    🌿 Veg
                                </span>
                            @endif
                        </div>
                        <span class="absolute bottom-3 right-3 bg-stone-900/80 backdrop-blur text-amber-300 text-xs font-black px-2.5 py-1 rounded-full" x-text="$store.currency.format({{ $dish->price_minor }})">
                            {{ $dish->formatted_price }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col flex-1 p-5 justify-between space-y-4">
                        <div>
                            <div class="text-[11px] font-bold text-[#0D4A2B] uppercase tracking-wider">
                                {{ $dish->category?->name }}
                            </div>
                            <a href="{{ route('menu.show', $dish->slug) }}" title="{{ $dish->name }}" class="block font-serif text-lg font-bold text-stone-900 hover:text-amber-600 transition-colors mt-1 line-clamp-1 truncate">
                                {{ $dish->name }}
                            </a>
                            <p class="text-xs text-stone-500 mt-2 line-clamp-2 leading-relaxed">
                                {{ $dish->description }}
                            </p>
                        </div>

                        <!-- Card Actions -->
                        <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-2">
                            <a href="{{ route('menu.show', $dish->slug) }}" class="text-xs font-bold text-stone-600 hover:text-stone-900">
                                View Details
                            </a>
                            <button
                                type="button"
                                x-data="{ inCart: $store.cart.isInCart({{ $dish->id }}) }"
                                x-init="$watch('$store.cart.items', () => { inCart = $store.cart.isInCart({{ $dish->id }}) })"
                                @click="if (!inCart) { $store.cart.addItem({ id: {{ $dish->id }}, name: '{{ addslashes($dish->name) }}', price: {{ $dish->price_minor }}, currency: '{{ $dish->currency }}', image: '{{ $dish->image }}' }); inCart = true; } else { $store.cart.isOpen = true; }"
                                :class="inCart ? 'bg-emerald-600 hover:bg-emerald-700 cursor-default' : 'bg-[#0D4A2B] hover:bg-amber-600'"
                                class="inline-flex items-center gap-1.5 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-xs transition-colors"
                            >
                                <span x-show="!inCart">+ Add to Tray</span>
                                <span x-show="inCart" x-cloak class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    In Tray
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-6">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection

