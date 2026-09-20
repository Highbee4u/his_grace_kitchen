@extends('layouts.storefront')

@section('title', 'His Grace Kitchen LTD | Authentic Nigerian Cuisine, Fast Delivery & Diaspora Shipping')
@section('meta_description', 'Experience authentic Nigerian cuisine: Firewood Smoky Jollof, Rich Egusi, Pounded Yam, Flame-Grilled Suya, and Owambe Event Catering. Express worldwide delivery.')

@push('json_ld')
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::restaurantSchema()) !!}
    @if(!empty($faqs))
        {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::faqSchema($faqs)) !!}
    @endif
@endpush

@section('content')
    <!-- Hero Experience Section -->
    <section class="relative bg-gradient-to-b from-stone-900 via-[#0a2e1b] to-stone-950 text-white overflow-hidden py-16 lg:py-24">
        <!-- Background Culinary Accents -->
        <div class="absolute inset-0 opacity-20 pointer-events-none mix-blend-luminosity bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1600&q=80');"></div>
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Column: Compelling Headline & CTAs -->
                <div class="lg:col-span-7 space-y-7 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-stone-800/80 border border-amber-400/30 px-3.5 py-1.5 rounded-full text-xs font-semibold text-amber-300 backdrop-blur-xs">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        Firewood Smoky Aroma • Slow-Simmered Native Soups • Diaspora Express
                    </div>

                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.12]">
                        Taste the Soul of <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-200 to-amber-500">Nigeria</span>, Cooked Fresh.
                    </h1>

                    <p class="text-stone-300 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        From firewood-smoked Party Jollof and velvety Egusi with pounded yam, to tender coal-grilled Beef Suya and Owambe party buffets. Delivered piping hot to your home or express air-shipped to your diaspora address in the UK, USA, Canada, and Europe.
                    </p>

                    <!-- Dual CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('menu.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-extrabold px-8 py-4 rounded-full text-base shadow-lg shadow-amber-500/20 hover:scale-[1.02] transition-all">
                            <span>Browse Delicious Menu</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>

                        <a href="{{ route('catering.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-7 py-4 rounded-full text-base backdrop-blur transition-all">
                            <span>Party & Event Catering</span>
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Highlight Badges -->
                    <div class="grid grid-cols-3 gap-3 pt-6 border-t border-white/10 max-w-lg mx-auto lg:mx-0 text-center">
                        <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                            <span class="block text-xl sm:text-2xl font-black text-amber-400 font-serif">100%</span>
                            <span class="text-[11px] text-stone-400 uppercase tracking-wider font-semibold">Authentic Recipes</span>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                            <span class="block text-xl sm:text-2xl font-black text-amber-400 font-serif">45 Min</span>
                            <span class="text-[11px] text-stone-400 uppercase tracking-wider font-semibold">Average Delivery</span>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                            <span class="block text-xl sm:text-2xl font-black text-amber-400 font-serif">4 Continents</span>
                            <span class="text-[11px] text-stone-400 uppercase tracking-wider font-semibold">Diaspora Express</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Hero Visual Food Showcase -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Main Hero Dish Card -->
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border-2 border-amber-400/20 bg-stone-900 group">
                            <img 
                                src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=900&q=80" 
                                alt="Signature Nigerian Smoky Party Jollof Rice" 
                                class="w-full h-96 object-cover object-center group-hover:scale-105 transition-transform duration-700"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/40 to-transparent"></div>
                            
                            <div class="absolute bottom-0 inset-x-0 p-6 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="bg-amber-500 text-stone-950 text-xs font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                                        Signature Dish
                                    </span>
                                    <span class="text-amber-400 text-sm font-bold" x-text="$store.currency.format(450000)">₦4,500</span>
                                </div>
                                <h3 class="font-serif text-2xl font-bold text-white">Smoky Wood-Fired Party Jollof</h3>
                                <p class="text-stone-300 text-xs line-clamp-2">Woodsmoke reduction with tatashe, scotch bonnet peppers, sweet dodo, and your choice of protein.</p>
                                <a 
                                    href="{{ route('menu.show', 'smoky-party-jollof-rice') }}" 
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-amber-300 pt-1"
                                >
                                    Order this dish →
                                </a>
                            </div>
                        </div>

                        <!-- Floating Review Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white text-stone-900 p-4 rounded-2xl shadow-xl border border-stone-100 hidden sm:flex items-center gap-3 max-w-xs animate-bounce-short">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#0D4A2B] flex items-center justify-center font-bold text-lg shrink-0">
                                🌶️
                            </div>
                            <div class="text-xs">
                                <div class="flex text-amber-500 font-bold">★★★★★</div>
                                <p class="font-bold text-stone-800">"Firewood smoke is real!"</p>
                                <p class="text-stone-500">Over 1,500+ happy clients</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Navigation Pills -->
    <section class="py-10 bg-white border-b border-stone-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xs uppercase font-extrabold tracking-widest text-amber-600">Menu Catalog</h2>
                    <p class="font-serif text-2xl sm:text-3xl font-bold text-stone-900">Explore Our Traditional Kitchen</p>
                </div>
                <a href="{{ route('menu.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-bold text-[#0D4A2B] hover:text-amber-600 transition-colors">
                    View Full Menu ({{ $signatureDishes->count() }}+ dishes) →
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach ($categories as $cat)
                    <a 
                        href="{{ route('menu.index', ['category' => $cat->slug]) }}" 
                        class="group flex flex-col p-4 rounded-2xl border border-stone-200/80 hover:border-amber-500 bg-stone-50 hover:bg-amber-50/40 transition-all hover:shadow-md"
                    >
                        <div class="w-10 h-10 rounded-xl bg-[#0D4A2B]/10 group-hover:bg-[#0D4A2B] text-[#0D4A2B] group-hover:text-amber-400 flex items-center justify-center font-bold mb-3 transition-colors">
                            @if ($cat->slug === 'rice-grains')
                                🍚
                            @elseif ($cat->slug === 'soups-swallows')
                                🍲
                            @elseif ($cat->slug === 'grills-street-food')
                                🍢
                            @elseif ($cat->slug === 'small-chops-sides')
                                🥟
                            @else
                                🍹
                            @endif
                        </div>
                        <h3 class="font-bold text-stone-900 text-sm group-hover:text-[#0D4A2B] transition-colors">{{ $cat->name }}</h3>
                        <p class="text-xs text-stone-500 mt-1">{{ $cat->items_count }} items</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Signature Dishes Grid -->
    <section class="py-16 bg-[#FCFBF7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <span class="text-xs uppercase font-extrabold tracking-widest text-[#0D4A2B]">Popular Selections</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-extrabold text-stone-900 mt-1">Signature Nigerian Dishes</h2>
                    <p class="text-stone-500 text-sm mt-2 max-w-xl">Every dish is prepared using fresh stone-ground scotch bonnets, native locust beans, crayfish, and local Nigerian herbs.</p>
                </div>
                <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 bg-[#0D4A2B] text-white px-5 py-2.5 rounded-full text-xs font-bold shadow hover:bg-[#09351e] self-start sm:self-auto">
                    Browse All Dishes
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($signatureDishes as $dish)
                    <div class="flex flex-col bg-white rounded-3xl overflow-hidden border border-stone-200/80 shadow-xs hover:shadow-xl transition-all duration-300 group">
                        <!-- Food Photo -->
                        <div class="relative aspect-4/3 overflow-hidden bg-stone-100">
                            <img 
                                src="{{ $dish->image }}" 
                                alt="{{ $dish->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                            
                            <!-- Badges -->
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
                            </div>

                            <span class="absolute bottom-3 right-3 bg-stone-900/80 backdrop-blur text-amber-300 text-xs font-black px-2.5 py-1 rounded-full" x-text="$store.currency.format({{ $dish->price_minor }})">
                                {{ $dish->formatted_price }}
                            </span>
                        </div>

                        <!-- Card Body -->
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

                            <!-- Action -->
                            <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-2">
                                <a href="{{ route('menu.show', $dish->slug) }}" class="text-xs font-bold text-stone-600 hover:text-stone-900">
                                    Customize
                                </a>
                                <button 
                                    type="button" 
                                    @click="$store.cart.addItem({
                                        id: {{ $dish->id }},
                                        name: '{{ addslashes($dish->name) }}',
                                        price: {{ $dish->price_minor }},
                                        currency: '{{ $dish->currency }}',
                                        image: '{{ $dish->image }}'
                                    })"
                                    class="inline-flex items-center gap-1.5 bg-[#0D4A2B] hover:bg-amber-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-xs transition-colors"
                                >
                                    <span>+ Add to Tray</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Curated Combo Deals Showcase -->
    @if ($combos->isNotEmpty())
        <section class="py-16 bg-stone-900 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <span class="text-xs uppercase font-extrabold tracking-widest text-amber-400">Bundle & Save</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-extrabold text-white">Curated Owambe Combo Packs</h2>
                    <p class="text-stone-400 text-sm">Perfect complete meals bundled with appetizers, mains, sides, and chilled beverages.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($combos as $combo)
                        <div class="bg-stone-800/80 rounded-3xl p-6 border border-stone-700/60 flex flex-col justify-between space-y-6 hover:border-amber-500/50 transition-all">
                            <div class="space-y-4">
                                <div class="aspect-16/9 rounded-2xl overflow-hidden bg-stone-700">
                                    <img src="{{ $combo->image }}" alt="{{ $combo->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex items-center justify-between">
                                    <h3 class="font-serif text-xl font-bold text-white">{{ $combo->name }}</h3>
                                    <span class="text-amber-400 font-extrabold text-lg" x-text="$store.currency.format({{ $combo->price_minor }})">{{ $combo->formatted_price }}</span>
                                </div>
                                <p class="text-stone-400 text-xs leading-relaxed">{{ $combo->description }}</p>

                                <!-- Included items pill list -->
                                @if (!empty($combo->items))
                                    <div class="space-y-1.5 pt-2">
                                        <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider">Includes:</span>
                                        <ul class="space-y-1">
                                            @foreach ((array)$combo->items as $item)
                                                <li class="flex items-center gap-2 text-xs text-stone-300">
                                                    <span class="text-emerald-400">✓</span> {{ $item }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <button 
                                type="button" 
                                @click="$store.cart.addItem({
                                    id: 'combo-{{ $combo->id }}',
                                    name: '{{ addslashes($combo->name) }}',
                                    price: {{ $combo->price_minor }},
                                    currency: '{{ $combo->currency }}',
                                    image: '{{ $combo->image }}',
                                    variant: 'Combo Pack'
                                })"
                                class="w-full bg-amber-500 hover:bg-amber-600 text-stone-950 font-extrabold text-sm py-3 rounded-xl transition-colors shadow-md"
                            >
                                Order Combo Tray
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Event Catering & Party Packages Section -->
    <section class="py-16 bg-gradient-to-b from-[#FCFBF7] to-amber-50/50 border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-[#0D4A2B] via-[#0f5431] to-[#0D4A2B] rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10 max-w-3xl space-y-5">
                    <span class="bg-amber-500 text-stone-950 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">
                        Events & Owambe
                    </span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                        Planning a Wedding, Birthday Gala, or Corporate Banquet?
                    </h2>
                    <p class="text-amber-100/90 text-sm sm:text-base leading-relaxed">
                        Treat your guests to unforgettable Nigerian hospitality. Full buffet setups, live flaming suya grilling stations, uniformed culinary service staff, and custom menus crafted by our executive chefs.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('catering.index') }}" class="bg-amber-500 hover:bg-amber-400 text-stone-950 font-bold px-6 py-3 rounded-full text-sm shadow-md transition-all">
                            View Catering Packages & Get Quote
                        </a>
                        <a href="{{ route('special-requests.create') }}" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold px-6 py-3 rounded-full text-sm backdrop-blur transition-all">
                            Request Custom Off-Menu Dish
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials & Customer Reviews (Auto-scrolling Carousel in groups of 3) -->
    <section 
        class="py-16 bg-white overflow-hidden" 
        x-data="{ 
            reviewModalOpen: false, 
            rating: 5, 
            hoverRating: 5,
            activeSlide: 0,
            totalSlides: {{ max(1, isset($reviewChunks) ? $reviewChunks->count() : 1) }},
            isPaused: false,
            timer: null,
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
            },
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
            },
            goTo(index) {
                this.activeSlide = index;
            },
            start() {
                this.stop();
                this.timer = setInterval(() => {
                    if (!this.isPaused && this.totalSlides > 1) {
                        this.next();
                    }
                }, 5000);
            },
            stop() {
                if (this.timer) clearInterval(this.timer);
            }
        }"
        x-init="start()"
        @mouseenter="isPaused = true"
        @mouseleave="isPaused = false"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div class="space-y-2">
                    <span class="text-xs uppercase font-extrabold tracking-widest text-[#0D4A2B]">Verified Reviews</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900">What Our Food Lovers Say</h2>
                    <p class="text-stone-500 text-xs sm:text-sm">Authentic stories from guests across Lagos, the UK, USA, and beyond.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button 
                        type="button" 
                        @click="reviewModalOpen = true" 
                        class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-5 py-2.5 rounded-full text-xs font-bold shadow-sm transition-all flex items-center gap-1.5"
                    >
                        <span>★ Write a Review</span>
                    </button>
                    <a 
                        href="{{ route('reviews.index') }}" 
                        class="bg-stone-100 hover:bg-stone-200 text-stone-700 px-5 py-2.5 rounded-full text-xs font-bold transition-all"
                    >
                        View All Reviews →
                    </a>
                </div>
            </div>

            @if (session('review_success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-xl text-xs text-emerald-800">
                    {{ session('review_success') }}
                </div>
            @endif

            <!-- Carousel Sliding Track Container (Single row of 3 cards per view) -->
            <div class="relative w-full overflow-hidden">
                <div 
                    class="flex transition-transform duration-700 ease-in-out"
                    :style="'transform: translateX(-' + (activeSlide * 100) + '%)'"
                >
                    @if (isset($reviewChunks))
                        @foreach ($reviewChunks as $chunkIndex => $chunk)
                            <div class="w-full shrink-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($chunk as $review)
                                    @if (is_object($review))
                                        <div class="bg-stone-50 rounded-3xl p-6 border border-stone-200/80 space-y-4 shadow-xs flex flex-col justify-between hover:border-amber-400/50 hover:shadow-md transition-all h-full">
                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <div class="text-amber-500 font-bold text-sm tracking-wider">
                                                        {{ $review->stars }}
                                                    </div>
                                                    @if ($review->is_verified_buyer)
                                                        <span class="text-[10px] font-extrabold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">
                                                            ✓ Verified Buyer
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($review->title)
                                                    <h4 class="font-serif font-bold text-stone-900 text-sm line-clamp-1">"{{ $review->title }}"</h4>
                                                @endif
                                                <p class="text-stone-700 text-xs sm:text-sm italic leading-relaxed line-clamp-4">"{{ $review->comment }}"</p>
                                            </div>
                                            <div class="pt-3 border-t border-stone-200/60 flex items-center justify-between text-xs">
                                                <div>
                                                    <span class="font-bold text-stone-900 block">{{ $review->customer_name }}</span>
                                                    <span class="text-stone-500 text-[11px]">{{ $review->customer_location ? $review->customer_location . ' • ' : '' }}{{ $review->dish_title }}</span>
                                                </div>
                                                <span class="text-[10px] text-stone-400">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-stone-50 rounded-3xl p-6 border border-stone-200/80 space-y-4 shadow-xs flex flex-col justify-between hover:border-amber-400/50 hover:shadow-md transition-all h-full">
                                            <div class="space-y-3">
                                                <div class="flex text-amber-500 text-sm font-bold">★★★★★</div>
                                                <p class="text-stone-700 text-xs sm:text-sm italic leading-relaxed line-clamp-4">"{{ $review['comment'] }}"</p>
                                            </div>
                                            <div class="pt-3 border-t border-stone-200/60 flex items-center justify-between text-xs">
                                                <div>
                                                    <span class="font-bold text-stone-900 block">{{ $review['name'] }}</span>
                                                    <span class="text-xs text-stone-500">{{ $review['location'] }} • {{ $review['dish'] }}</span>
                                                </div>
                                                <span class="text-[10px] text-emerald-700 font-bold">Verified</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Carousel Navigator (Arrows & Indicator Dots) -->
            <div class="flex items-center justify-center gap-4 pt-2">
                <!-- Prev Button -->
                <button 
                    type="button" 
                    @click="prev()"
                    class="w-9 h-9 rounded-full bg-stone-100 hover:bg-[#0D4A2B] hover:text-white text-stone-700 flex items-center justify-center transition-all shadow-xs cursor-pointer"
                    aria-label="Previous reviews"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Indicator Dots -->
                <div class="flex items-center gap-2">
                    <template x-for="(chunk, idx) in totalSlides" :key="idx">
                        <button 
                            type="button"
                            @click="goTo(idx)"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                            :class="activeSlide === idx ? 'w-8 bg-[#0D4A2B]' : 'w-2 bg-stone-300 hover:bg-stone-400'"
                            :aria-label="'Go to review slide ' + (idx + 1)"
                        ></button>
                    </template>
                </div>

                <!-- Next Button -->
                <button 
                    type="button" 
                    @click="next()"
                    class="w-9 h-9 rounded-full bg-stone-100 hover:bg-[#0D4A2B] hover:text-white text-stone-700 flex items-center justify-center transition-all shadow-xs cursor-pointer"
                    aria-label="Next reviews"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Interactive Review Submission Modal -->
        <div 
            x-show="reviewModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-950/60 backdrop-blur-xs"
        >
            <div 
                @click.outside="reviewModalOpen = false" 
                class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-stone-200 max-h-[90vh] overflow-y-auto space-y-5"
            >
                <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#0D4A2B]">Guest Dining Feedback</span>
                        <h3 class="font-serif text-2xl font-bold text-stone-900">Share Your Experience</h3>
                    </div>
                    <button type="button" @click="reviewModalOpen = false" class="text-stone-400 hover:text-stone-600 text-xl font-bold">✕</button>
                </div>

                <form method="POST" action="{{ route('reviews.store') }}" class="space-y-4">
                    @csrf
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                    <!-- Star Picker -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">
                            Your Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-1">
                            <template x-for="i in 5" :key="i">
                                <button 
                                    type="button" 
                                    @click="rating = i; hoverRating = i;"
                                    @mouseenter="hoverRating = i"
                                    @mouseleave="hoverRating = rating"
                                    class="text-2xl transition-transform hover:scale-110 focus:outline-hidden"
                                    :class="i <= hoverRating ? 'text-amber-400' : 'text-stone-200'"
                                >
                                    ★
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="rating">
                            <span class="ml-2 text-xs font-bold text-stone-600" x-text="rating + ' / 5 Stars'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Name <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" value="{{ auth()->user()?->name }}" required placeholder="e.g. Tunde Bakare" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Location</label>
                            <input type="text" name="customer_location" placeholder="e.g. Victoria Island, Lagos" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Email (Private)</label>
                            <input type="email" name="customer_email" value="{{ auth()->user()?->email }}" placeholder="tunde@example.com" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Dish Enjoyed</label>
                            <select name="menu_item_id" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                                <option value="">Select dish (optional)...</option>
                                @if(isset($allDishes))
                                    @foreach($allDishes as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Headline (Optional)</label>
                        <input type="text" name="title" placeholder="e.g. Unforgettable Egusi & Pounded Yam!" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Review <span class="text-red-500">*</span></label>
                        <textarea name="comment" rows="3" required placeholder="Tell other food lovers about the flavors, warmth, and spices..." class="w-full rounded-xl border-stone-300 text-xs p-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="reviewModalOpen = false" class="px-4 py-2 rounded-full text-xs font-bold text-stone-600 hover:text-stone-900">Cancel</button>
                        <button type="submit" class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-6 py-2.5 rounded-full text-xs font-bold shadow transition-all">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- FAQs Accordion -->
    @if (!empty($faqs))
        <section class="py-16 bg-[#FCFBF7] border-t border-stone-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="text-center space-y-2">
                    <span class="text-xs uppercase font-extrabold tracking-widest text-[#0D4A2B]">Have Questions?</span>
                    <h2 class="font-serif text-3xl font-bold text-stone-900">Frequently Asked Questions</h2>
                </div>

                <div class="space-y-4" x-data="{ active: null }">
                    @foreach ($faqs as $idx => $faq)
                        <div class="bg-white rounded-2xl border border-stone-200/80 overflow-hidden shadow-xs">
                            <button 
                                type="button" 
                                @click="active = (active === {{ $idx }} ? null : {{ $idx }})"
                                class="w-full flex items-center justify-between p-5 text-left font-bold text-stone-900 text-sm hover:text-[#0D4A2B]"
                            >
                                <span>{{ $faq['question'] }}</span>
                                <span class="text-amber-600 text-lg font-bold" x-text="active === {{ $idx }} ? '−' : '+'"></span>
                            </button>
                            <div x-show="active === {{ $idx }}" x-collapse class="px-5 pb-5 text-xs sm:text-sm text-stone-600 leading-relaxed border-t border-stone-100 pt-3">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

