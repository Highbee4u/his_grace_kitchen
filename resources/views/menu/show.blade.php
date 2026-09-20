@extends('layouts.storefront')

@section('title', $item->name . ' | Nigerian Kitchen Authentic Delicacy')
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
                return '₦' + ((this.unitPrice * this.quantity) / 100).toLocaleString('en-US', { minimumFractionDigits: 2 });
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
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
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
                                    <span class="text-stone-500 font-normal">
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
                                    <span class="text-stone-600 font-bold">
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
                        @click="$store.cart.addItem({
                            id: {{ $item->id }},
                            name: '{{ addslashes($item->name) }}',
                            price: unitPrice,
                            currency: '{{ $item->currency }}',
                            image: '{{ $item->image }}',
                            variant: selectedVariant,
                            quantity: quantity
                        })"
                        class="flex-1 w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-[#0D4A2B] hover:bg-[#09351e] text-white py-4 px-8 rounded-2xl font-extrabold text-base shadow-lg shadow-emerald-900/20 transition-all transform active:scale-95"
                    >
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Add to Food Tray • <span x-text="formattedTotal"></span></span>
                    </button>
                </div>
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
                            <span class="text-xs font-bold text-[#0D4A2B] mt-1 block">{{ $rel->formatted_price }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

