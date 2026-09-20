@extends('layouts.storefront')

@section('title', 'Our Story & Culinary Heritage | Nigerian Kitchen')
@section('meta_description', 'Discover the story behind Nigerian Kitchen: authentic woodfire jollof rice, native simmered soups, hand-ground spices, and connecting the diaspora back to home.')

@push('json_ld')
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::breadcrumbSchema([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Our Story', 'url' => route('about')],
    ])) !!}
@endpush

@section('content')
    <!-- Hero Banner -->
    <section class="relative bg-gradient-to-b from-stone-950 via-[#0D4A2B] to-stone-900 text-white py-20 sm:py-28 overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#F59E0B_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 border border-amber-500/30 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
                Our Roots & Mission
            </span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mb-6">
                The Soul of <span class="text-amber-400">Authentic Nigerian Gastronomy</span>
            </h1>
            <p class="text-stone-300 text-base sm:text-xl max-w-3xl mx-auto font-light leading-relaxed">
                We founded Nigerian Kitchen with an unyielding obsession: preserving the true, untamed aromas of traditional Nigerian home cooking and bringing them hot to tables in Lagos, London, New York, and beyond.
            </p>
        </div>
    </section>

    <!-- Narrative Section: The Firewood Philosophy -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-6">
                    <span class="text-amber-600 font-bold uppercase tracking-widest text-xs">Firewood & Heritage</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 leading-tight">
                        No Shortcuts. Real Firewood Smoke and Pure Stone-Ground Native Spices.
                    </h2>
                    <p class="text-stone-600 leading-relaxed text-sm sm:text-base">
                        In many modern kitchens, the legendary "party jollof" smoky aroma is imitated with artificial additives. At Nigerian Kitchen, we reject shortcuts. Our signature party jollof is slow-cooked over woodfire in cast-iron cauldrons, allowing sweet tatashe (bell peppers), fiery scotch bonnets, onions, and rich stock to caramelise naturally.
                    </p>
                    <p class="text-stone-600 leading-relaxed text-sm sm:text-base">
                        Our native soups—Egusi, Efo Riro, Ogbono, and Banga—are simmered for hours using cold-pressed palm oil from eastern groves, whole smoked catfish, dry prawns, fermented locust beans (iru), and aromatic uziza leaves. Every mouthful tastes like home.
                    </p>
                    <div class="pt-2 flex items-center gap-6">
                        <div>
                            <span class="block font-serif text-3xl font-extrabold text-[#0D4A2B]">100%</span>
                            <span class="text-xs text-stone-500 font-medium">Native Ingredients</span>
                        </div>
                        <div class="w-px h-10 bg-stone-200"></div>
                        <div>
                            <span class="block font-serif text-3xl font-extrabold text-[#0D4A2B]">6+ Hours</span>
                            <span class="text-xs text-stone-500 font-medium">Slow-simmered Stocks</span>
                        </div>
                        <div class="w-px h-10 bg-stone-200"></div>
                        <div>
                            <span class="block font-serif text-3xl font-extrabold text-[#0D4A2B]">48h</span>
                            <span class="text-xs text-stone-500 font-medium">Diaspora Courier</span>
                        </div>
                    </div>
                </div>

                <!-- Visual Mosaic -->
                <div class="relative">
                    <div class="aspect-4/3 rounded-3xl overflow-hidden shadow-2xl border-4 border-amber-500/20">
                        <img 
                            src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1200&q=80" 
                            alt="Nigerian Food Feast" 
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-stone-900 text-white p-6 rounded-2xl shadow-xl max-w-xs border border-stone-800 hidden sm:block">
                        <p class="font-serif italic text-amber-300 text-sm">
                            "Food is culture, memory, and homecoming. We deliver that memory every single day."
                        </p>
                        <span class="text-xs text-stone-400 mt-2 block font-semibold">— Head Chef & Founder</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Diaspora Connection Section -->
    <section class="py-20 bg-stone-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1 aspect-4/3 rounded-3xl overflow-hidden shadow-xl">
                    <img 
                        src="https://images.unsplash.com/photo-1505576399279-565b52d4ac71?auto=format&fit=crop&w=1200&q=80" 
                        alt="Diaspora Express Chilled Packing" 
                        class="w-full h-full object-cover"
                    >
                </div>
                <div class="order-1 lg:order-2 space-y-6">
                    <span class="text-emerald-700 font-bold uppercase tracking-widest text-xs">Global Diaspora Logistics</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 leading-tight">
                        Connecting the Global Diaspora to the Flavors of Nigeria
                    </h2>
                    <p class="text-stone-600 leading-relaxed text-sm sm:text-base">
                        Living abroad in London, Houston, Manchester, or Toronto should never mean settling for bland substitutes or longing for motherland stews.
                    </p>
                    <p class="text-stone-600 leading-relaxed text-sm sm:text-base">
                        We developed a proprietary cold-chain packaging workflow: meals are prepared fresh in our central kitchen, rapid blast-chilled to lock in taste and nutrients, sealed in vacuum containers, packed with dry ice in insulated thermal pods, and expedited via express air cargo directly to your doorstep.
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('catering.index') }}" class="inline-flex items-center gap-2 bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white px-6 py-3.5 rounded-full font-bold text-sm shadow transition-all">
                            <span>Explore Diaspora Banquets</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Four Pillars Grid -->
    <section class="py-20 bg-[#FCFBF7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-amber-600 font-bold uppercase tracking-widest text-xs">Our Standard</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 mt-2">
                    The Four Pillars of Nigerian Kitchen
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-stone-200 shadow-sm text-center">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        🌿
                    </div>
                    <h3 class="font-serif text-lg font-bold text-stone-900 mb-2">Native Sourcing</h3>
                    <p class="text-stone-500 text-xs leading-relaxed">Direct relationships with local market farmers in Oyo, Delta, and Cross River states.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-stone-200 shadow-sm text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        🥘
                    </div>
                    <h3 class="font-serif text-lg font-bold text-stone-900 mb-2">Slow Artisanal Craft</h3>
                    <p class="text-stone-500 text-xs leading-relaxed">No rushed pots. Simmered stocks, slow-cooked meats, and woodfire cauldron reduction.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-stone-200 shadow-sm text-center">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        🛡️
                    </div>
                    <h3 class="font-serif text-lg font-bold text-stone-900 mb-2">Food Safety Assured</h3>
                    <p class="text-stone-500 text-xs leading-relaxed">Certified industrial hygienic prep, tamper-evident seals, and cold-chain compliance.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-stone-200 shadow-sm text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                        🌍
                    </div>
                    <h3 class="font-serif text-lg font-bold text-stone-900 mb-2">Multi-Currency Global</h3>
                    <p class="text-stone-500 text-xs leading-relaxed">Seamless checkout in NGN, GBP, USD, CAD, EUR with transparent courier tracking.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA -->
    <section class="bg-[#0D4A2B] text-white py-16 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-serif text-3xl sm:text-4xl font-bold">Ready to Taste the Authentic Difference?</h2>
            <p class="text-stone-300 text-sm sm:text-base max-w-xl mx-auto">
                Explore our full menu of over 30 authentic dishes, or contact our catering team for your upcoming Owambe celebration.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="{{ route('menu.index') }}" class="bg-amber-500 hover:bg-amber-600 text-stone-950 font-bold px-8 py-3.5 rounded-full text-sm shadow transition-all">
                    Browse the Menu
                </a>
                <a href="{{ route('contact') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-8 py-3.5 rounded-full text-sm border border-white/20 transition-all">
                    Contact Kitchen
                </a>
            </div>
        </div>
    </section>
@endsection

