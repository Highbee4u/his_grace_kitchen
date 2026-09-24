@extends('layouts.storefront')

@section('title', $item->name . ' | His Grace Kitchen LTD Authentic Delicacy')
@section('meta_description', Str::limit(strip_tags($item->description ?? $item->name . ' prepared freshly with authentic Nigerian spices and ingredients.'), 155))
@section('og_image', $item->image_url ?? asset('images/hero-jollof.jpg'))
@section('og_type', 'restaurant.menu_item')

@push('json_ld')
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::menuItemSchema($item)) !!}
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::breadcrumbSchema([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Menu', 'url' => route('menu.index')],
        ['name' => $item->category?->name ?? 'Dishes', 'url' => route('menu.index', ['category' => $item->category?->slug])],
        ['name' => $item->name, 'url' => route('menu.show', $item->slug)],
    ])) !!}
@endpush

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-stone-900">Home</a>
        <span>/</span>
        <a href="{{ route('menu.index') }}" class="hover:text-stone-900">Menu</a>
        <span>/</span>
        <a href="{{ route('menu.index', ['category' => $item->category?->slug]) }}" class="hover:text-stone-900">{{ $item->category?->name }}</a>
        <span>/</span>
        <span class="text-stone-900">{{ $item->name }}</span>
    </nav>

    <!-- Dish Product Details Box (Alpine Interactive Component) -->
    <div 
        class="bg-white rounded-3xl border border-stone-200/80 shadow-xs p-6 lg:p-10"
        x-data="{
            basePrice: {{ $item->price_minor }},
            selectedVariant: '{{ $item->variants->where('is_default', true)->first()?->name ?? ($item->variants->first()?->name ?? '') }}',
            variantPrice: {{ $item->variants->where('is_default', true)->first()?->price_minor ?? ($item->variants->first()?->price_minor ?? 0) }},
            selectedAddOns: [],
            quantity: 1,

            get unitPrice() {
                let addOnsTotal = this.selectedAddOns.reduce((sum, item) => sum + item.price, 0);
                return this.basePrice + this.variantPrice + addOnsTotal;
            },

            get formattedTotal() {
                return Alpine.store('currency').format(this.unitPrice * this.quantity);
            },

            toggleAddOn(name, price) {
                const idx = this.selectedAddOns.findIndex(a => a.name === name);
                if (idx > -1) {
                    this.selectedAddOns.splice(idx, 1);
                } else {
                    this.selectedAddOns.push({ name, price });
                }
            }
        }"
    >
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
            <!-- Left: Dish Photo & Badges -->
            <div class="lg:col-span-6 space-y-4">
                <div class="relative aspect-4/3 rounded-3xl overflow-hidden bg-stone-100 shadow-md">
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover" width="900" height="675" fetchpriority="high" decoding="async">
                    <div class="absolute top-4 left-4 flex flex-wrap gap-1.5">
                        @if ($item->spice_level && $item->spice_level !== 'None')
                            <span class="bg-red-700 text-white font-bold text-xs px-3 py-1 rounded-full shadow-xs">
                                {{ $item->spice_level }} Heat 🌶️
                            </span>
                        @endif
                        @if ($item->is_vegetarian)
                            <span class="bg-emerald-700 text-white font-bold text-xs px-3 py-1 rounded-full shadow-xs">
                                Vegetarian Friendly 🌱
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Quality Highlights -->
                <div class="grid grid-cols-3 gap-3 text-center text-xs">
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100">
                        <span class="block font-bold text-stone-900">Firewood Touch</span>
                        <span class="text-stone-500 text-[11px]">Authentic Taste</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100">
                        <span class="block font-bold text-stone-900">Fresh Daily</span>
                        <span class="text-stone-500 text-[11px]">Cooked to Order</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-100">
                        <span class="block font-bold text-stone-900">Diaspora Safe</span>
                        <span class="text-stone-500 text-[11px]">Thermal Packed</span>
                    </div>
                </div>
            </div>

            <!-- Right: Options, Pricing & Add to Tray -->
            <div class="lg:col-span-6 space-y-7">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#0D4A2B]">
                        {{ $item->category?->name }}
                    </span>
                    <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-stone-900 mt-1">
                        {{ $item->name }}
                    </h1>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="flex text-amber-500 text-xs">★★★★★</div>
                        <span class="text-xs font-bold text-stone-800">{{ $item->average_rating }}</span>
                        <a href="#dish-reviews" class="text-xs text-stone-500 hover:text-[#0D4A2B] underline">
                            ({{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }})
                        </a>
                    </div>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="text-2xl sm:text-3xl font-black text-[#0D4A2B]" x-text="formattedTotal">
                            {{ $item->formatted_price }}
                        </span>
                        <span class="text-xs text-stone-400 font-medium">Base dish starting rate</span>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-stone-600 text-sm leading-relaxed">
                    {{ $item->description }}
                </p>

                <!-- Allergens Warning -->
                @if (!empty($item->allergens) && !in_array('None', (array)$item->allergens))
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2">
                        <span class="text-amber-700 font-bold">Allergens Notice:</span>
                        <span>Contains {{ implode(', ', (array)$item->allergens) }}</span>
                    </div>
                @endif

                <!-- Protein / Variant Picker -->
                @if ($item->variants->isNotEmpty())
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-stone-900">
                            1. Select Your Protein / Variant:
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach ($item->variants as $variant)
                                <label 
                                    class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition-all text-xs"
                                    :class="selectedVariant === '{{ $variant->name }}' ? 'border-[#0D4A2B] bg-emerald-50/50 font-bold text-[#0D4A2B]' : 'border-stone-200 hover:border-stone-300 text-stone-700'"
                                >
                                    <div class="flex items-center gap-2">
                                        <input 
                                            type="radio" 
                                            name="variant" 
                                            value="{{ $variant->name }}" 
                                            class="text-[#0D4A2B] focus:ring-[#0D4A2B]"
                                            @change="selectedVariant = '{{ $variant->name }}'; variantPrice = {{ $variant->price_minor }}"
                                            :checked="selectedVariant === '{{ $variant->name }}'"
                                        >
                                        <span>{{ $variant->name }}</span>
                                    </div>
                                    <span class="text-stone-500 font-normal" x-text="{{ $variant->price_minor > 0 ? '\'+\' + $store.currency.format(' . $variant->price_minor . ')' : '\'Included\'' }}">
                                        {{ $variant->price_minor > 0 ? '+'.\App\Support\Money::format($variant->price_minor, $variant->currency) : 'Included' }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add-ons Selection -->
                @if ($addOns->isNotEmpty())
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-stone-900">
                            2. Extra Sides & Add-ons (Optional):
                        </label>
                        <div class="space-y-2">
                            @foreach ($addOns as $addon)
                                <label class="flex items-center justify-between p-3 rounded-xl border border-stone-200 hover:border-stone-300 cursor-pointer text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <input 
                                            type="checkbox" 
                                            class="rounded border-stone-300 text-[#0D4A2B] focus:ring-[#0D4A2B]"
                                            @change="toggleAddOn('{{ addslashes($addon->name) }}', {{ $addon->price_minor }})"
                                        >
                                        <span class="text-stone-800 font-medium">{{ $addon->name }}</span>
                                    </div>
                                    <span class="text-stone-600 font-bold" x-text="'+' + $store.currency.format({{ $addon->price_minor }})">
                                        +{{ \App\Support\Money::format($addon->price_minor, $addon->currency) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Stepper and Add To Cart CTA -->
                <div class="pt-4 border-t border-stone-200 flex flex-col sm:flex-row items-center gap-4">
                    <!-- Quantity Stepper -->
                    <div class="flex items-center border border-stone-300 rounded-2xl p-1 bg-stone-50">
                        <button 
                            type="button" 
                            @click="quantity = Math.max(1, quantity - 1)" 
                            class="w-10 h-10 rounded-xl bg-white hover:bg-stone-200 flex items-center justify-center font-bold text-stone-800 shadow-xs"
                        >−</button>
                        <span class="w-12 text-center font-bold text-base text-stone-900" x-text="quantity"></span>
                        <button 
                            type="button" 
                            @click="quantity = quantity + 1" 
                            class="w-10 h-10 rounded-xl bg-white hover:bg-stone-200 flex items-center justify-center font-bold text-stone-800 shadow-xs"
                        >+</button>
                    </div>

                    <!-- Add Button -->
                    <button 
                        type="button" 
                        :disabled="$store.cart.hasItem({{ $item->id }})"
                        @click="$store.cart.addItem({
                            id: {{ $item->id }},
                            name: '{{ addslashes($item->name) }}',
                            price: unitPrice,
                            currency: '{{ $item->currency }}',
                            image: '{{ $item->image }}',
                            variant: selectedVariant,
                            addOns: selectedAddOns.map(addOn => addOn.name),
                            quantity: quantity
                        })"
                        :class="$store.cart.hasItem({{ $item->id }}) ? 'bg-stone-300 text-stone-600 cursor-not-allowed' : 'bg-[#0D4A2B] hover:bg-[#09351e] text-white'"
                        class="flex-1 w-full sm:w-auto inline-flex items-center justify-center gap-3 py-4 px-8 rounded-2xl font-extrabold text-base shadow-lg shadow-emerald-900/20 transition-all transform active:scale-95"
                    >
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-text="$store.cart.hasItem({{ $item->id }}) ? 'Already in Tray' : 'Add to Food Tray • ' + formattedTotal"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Reviews & Ratings for this Dish -->
    <div id="dish-reviews" class="space-y-6 pt-6" x-data="{ dishReviewModalOpen: false, dishRating: 5, dishHoverRating: 5 }">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/80 shadow-xs">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-stone-100 pb-6 mb-6">
                <div>
                    <span class="text-xs font-bold text-[#0D4A2B] uppercase tracking-wider">Customer Experience</span>
                    <h3 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">
                        Reviews & Ratings for {{ $item->name }}
                    </h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-2xl font-bold text-stone-900 font-serif">{{ $item->average_rating }}</span>
                        <div class="text-amber-400 text-lg">★★★★★</div>
                        <span class="text-xs text-stone-500 font-medium">Based on {{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}</span>
                    </div>
                </div>

                <button 
                    type="button" 
                    @click="dishReviewModalOpen = true" 
                    class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-5 py-2.5 rounded-full text-xs font-bold shadow transition-all flex items-center gap-1.5"
                >
                    <span>★ Review this Dish</span>
                </button>
            </div>

            @if (session('review_success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-xl text-xs text-emerald-800 mb-6">
                    {{ session('review_success') }}
                </div>
            @endif

            @if ($reviews->isEmpty())
                <div class="text-center py-8 text-stone-500">
                    <p class="text-sm font-semibold text-stone-700">No reviews yet for {{ $item->name }}.</p>
                    <p class="text-xs text-stone-500 mt-1">Have you savored this meal? Be the very first customer to share your thoughts!</p>
                    <button 
                        type="button" 
                        @click="dishReviewModalOpen = true" 
                        class="mt-3 inline-block bg-amber-500 hover:bg-amber-600 text-stone-950 px-5 py-2 rounded-full text-xs font-bold transition-all shadow-xs"
                    >
                        Write the First Review
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($reviews as $rev)
                        <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200/80 space-y-3 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-amber-500 font-bold text-xs">{{ $rev->stars }}</span>
                                    @if ($rev->is_verified_buyer)
                                        <span class="text-[10px] font-extrabold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">
                                            ✓ Verified Buyer
                                        </span>
                                    @endif
                                </div>
                                @if ($rev->title)
                                    <h5 class="font-serif font-bold text-stone-900 text-sm">"{{ $rev->title }}"</h5>
                                @endif
                                <p class="text-stone-700 text-xs italic leading-relaxed">"{{ $rev->comment }}"</p>

                                @if ($rev->admin_response)
                                    <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-stone-800 space-y-0.5">
                                        <span class="font-bold text-[#0D4A2B] block">👨‍🍳 Chef's Note:</span>
                                        <p class="text-stone-600">{{ $rev->admin_response }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-2 border-t border-stone-200/60 flex items-center justify-between text-[11px] text-stone-500">
                                <span class="font-bold text-stone-800">{{ $rev->customer_name }} {{ $rev->customer_location ? '• ' . $rev->customer_location : '' }}</span>
                                <span class="text-stone-400">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Write Dish Review Modal -->
        <div 
            x-show="dishReviewModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-950/60 backdrop-blur-xs"
        >
            <div 
                @click.outside="dishReviewModalOpen = false" 
                class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-stone-200 space-y-4 max-h-[90vh] overflow-y-auto"
            >
                <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#0D4A2B]">Dish Review</span>
                        <h4 class="font-serif text-xl font-bold text-stone-900">{{ $item->name }}</h4>
                    </div>
                    <button type="button" @click="dishReviewModalOpen = false" class="text-stone-400 hover:text-stone-600 text-xl font-bold">✕</button>
                </div>

                <form method="POST" action="{{ route('reviews.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                    <input type="hidden" name="dish_name" value="{{ $item->name }}">
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-1">
                            <template x-for="i in 5" :key="i">
                                <button 
                                    type="button" 
                                    @click="dishRating = i; dishHoverRating = i;"
                                    @mouseenter="dishHoverRating = i"
                                    @mouseleave="dishHoverRating = dishRating"
                                    class="text-2xl transition-transform hover:scale-110 focus:outline-hidden"
                                    :class="i <= dishHoverRating ? 'text-amber-400' : 'text-stone-200'"
                                >
                                    ★
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="dishRating">
                            <span class="ml-2 text-xs font-bold text-stone-600" x-text="dishRating + ' / 5 Stars'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Name <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" value="{{ auth()->user()?->name }}" required placeholder="e.g. Adeola Bello" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Location</label>
                            <input type="text" name="customer_location" placeholder="e.g. Lekki, Lagos" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Email (Private)</label>
                            <input type="email" name="customer_email" value="{{ auth()->user()?->email }}" placeholder="adeola@example.com" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Order # (Optional)</label>
                            <input type="text" name="order_number" placeholder="NK-..." class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Headline (Optional)</label>
                        <input type="text" name="title" placeholder="e.g. Unbelievable woodsmoke flavor!" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Review <span class="text-red-500">*</span></label>
                        <textarea name="comment" rows="3" required placeholder="How were the spices, tender meat, and packaging?" class="w-full rounded-xl border-stone-300 text-xs p-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="dishReviewModalOpen = false" class="px-4 py-2 rounded-full text-xs font-bold text-stone-600 hover:text-stone-900">Cancel</button>
                        <button type="submit" class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-6 py-2.5 rounded-full text-xs font-bold shadow transition-all">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Related Dishes -->
    @if ($relatedItems->isNotEmpty())
        <div class="space-y-6 pt-6">
            <h3 class="font-serif text-2xl font-bold text-stone-900">You Might Also Crave</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ($relatedItems as $rel)
                    <a href="{{ route('menu.show', $rel->slug) }}" class="bg-white rounded-2xl overflow-hidden border border-stone-200/80 p-4 flex items-center gap-4 hover:shadow-md transition-all group">
                        <img src="{{ $rel->image }}" alt="{{ $rel->name }}" class="w-20 h-20 rounded-xl object-cover shrink-0">
                        <div>
                            <h4 class="font-serif font-bold text-stone-900 group-hover:text-amber-600 transition-colors">{{ $rel->name }}</h4>
                            <span class="text-xs font-bold text-[#0D4A2B] mt-1 block" x-text="$store.currency.format({{ $rel->price_minor }})">{{ $rel->formatted_price }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

