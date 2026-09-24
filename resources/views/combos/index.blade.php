@extends('layouts.storefront')

@section('title', 'Combos & Feast Boxes | His Grace Kitchen LTD')
@section('meta_description', 'Curated Nigerian food combo boxes for London: Owambe Party Box, Big Swallow Feast, Suya & Street Chops Platter, and Ofada King Feast.')

@push('json_ld')
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::breadcrumbSchema([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Combos & Feasts', 'url' => route('combos.index')],
    ])) !!}
@endpush

@section('content')
    <!-- Combos Hero -->
    <section class="relative bg-gradient-to-b from-stone-900 via-[#0D4A2B]/90 to-stone-900 text-white py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#F59E0B_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 bg-amber-500/20 text-amber-300 border border-amber-500/30 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                </svg>
                Bundle & Save Up to 25%
            </span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mb-6">
                Curated Combos & <span class="text-amber-400">Feast Boxes</span>
            </h1>
            <p class="text-stone-300 text-base sm:text-lg max-w-2xl mx-auto font-light leading-relaxed">
                Hand-picked combinations pairing our iconic smoky jollof, slow-simmered native soups, and sizzling grills into ready-to-enjoy culinary experiences for solo indulgence, couples, and group feasts.
            </p>
        </div>
    </section>

    <!-- Combos Grid Section -->
    <section class="py-16 sm:py-20 bg-[#FCFBF7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
                @forelse($combos as $combo)
                    <div class="bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-stone-200/80 flex flex-col group">
                        <!-- Combo Image -->
                        <div class="relative h-64 sm:h-72 overflow-hidden bg-stone-100">
                            <img 
                                src="{{ $combo->image }}" 
                                alt="{{ $combo->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                                decoding="async"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-950/80 via-transparent to-transparent"></div>

                            <!-- Price Badge -->
                            <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between text-white">
                                <div>
                                    <span class="text-xs uppercase font-bold tracking-widest text-amber-400 block mb-0.5">Bundle Price</span>
                                    <span class="text-2xl sm:text-3xl font-serif font-extrabold" x-text="$store.currency.format({{ $combo->price_minor }})">{{ $combo->formatted_price }}</span>
                                </div>
                                <span class="bg-amber-500 text-stone-950 text-xs font-extrabold uppercase px-3 py-1.5 rounded-full shadow-md">
                                    Full Feast
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-serif text-2xl font-bold text-stone-900 group-hover:text-[#0D4A2B] transition-colors mb-3">
                                    {{ $combo->name }}
                                </h3>
                                <p class="text-stone-600 text-sm leading-relaxed mb-6">
                                    {{ $combo->description }}
                                </p>

                                <!-- What's Included -->
                                @if(!empty($combo->items) && is_array($combo->items))
                                    <div class="bg-amber-50/70 border border-amber-200/60 rounded-2xl p-4 mb-6">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-amber-900 mb-3 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            What's Inside This Box:
                                        </h4>
                                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-medium text-stone-700">
                                            @foreach($combo->items as $item)
                                                <li class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 shrink-0"></span>
                                                    <span>{{ $item }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Customisation Slots -->
                                @if(!empty($combo->customisation_slots) && is_array($combo->customisation_slots))
                                    <div class="flex flex-wrap items-center gap-2 mb-6">
                                        <span class="text-xs font-semibold text-stone-500">Customisable:</span>
                                        @foreach($combo->customisation_slots as $slot)
                                            <span class="inline-flex items-center gap-1 bg-stone-100 text-stone-700 text-xs px-2.5 py-1 rounded-md font-medium">
                                                <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $slot }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-6 border-t border-stone-100 flex flex-col sm:flex-row items-center gap-3">
                                <button
                                    type="button"
                                    x-data="{ inCart: $store.cart.isInCart('combo-{{ $combo->id }}', 'Combo Feast Box') }"
                                    x-init="$watch('$store.cart.items', () => { inCart = $store.cart.isInCart('combo-{{ $combo->id }}', 'Combo Feast Box') })"
                                    :disabled="inCart"
                                    @click="$store.cart.addItem({ id: 'combo-{{ $combo->id }}', name: '{{ addslashes($combo->name) }}', price: {{ $combo->price_minor }}, image: '{{ $combo->image }}', variant: 'Combo Feast Box' }); inCart = true"
                                    :class="inCart ? 'bg-stone-300 text-stone-600 cursor-not-allowed' : 'bg-[#0D4A2B] hover:bg-[#09351e]'"
                                    class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 text-white py-3.5 px-5 rounded-xl font-bold text-sm shadow-md transition-all duration-150 transform active:scale-98"
                                >
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <template x-if="!inCart">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            Add Combo to Tray
                                        </span>
                                    </template>
                                    <template x-if="inCart">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Already in Tray
                                        </span>
                                    </template>
                                </button>
                                <a 
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber ?? '447988575682') }}?text={{ urlencode('Hello His Grace Kitchen! I would like to order the ' . $combo->name . ' combo feast.') }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 py-3.5 px-4 rounded-xl font-semibold text-sm transition-colors"
                                >
                                    <svg class="w-4 h-4 fill-emerald-600" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                    </svg>
                                    WhatsApp Order
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-16 bg-white rounded-3xl border border-stone-200">
                        <p class="text-stone-500">No combo bundles currently active. Please check back shortly!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Bottom Catering Teaser Banner -->
    <section class="bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 py-12 text-stone-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <div>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold">Hosting an Owambe, Wedding, or Corporate Event?</h3>
                <p class="text-amber-950/80 text-sm sm:text-base mt-1">
                    We cater for 15 to 1,000+ guests with full buffet setups, live suya stations, and uniformed staff.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('catering.index') }}" class="bg-[#0D4A2B] hover:bg-[#08301b] text-white px-6 py-3.5 rounded-full font-bold text-sm shadow-md transition-all">
                    Explore Event Catering
                </a>
                <a href="{{ route('special-requests.create') }}" class="bg-white/90 hover:bg-white text-stone-900 px-5 py-3.5 rounded-full font-bold text-sm transition-all">
                    Custom Dishes
                </a>
            </div>
        </div>
    </section>
@endsection

