@extends('layouts.storefront')

@section('title', 'Secure Checkout | Nigerian Kitchen')
@section('meta_description', 'Complete your authentic Nigerian food order with fast local delivery or international diaspora courier.')

@section('content')
    <div class="bg-[#FCFBF7] py-12 sm:py-16" x-data="{
        fulfilmentType: '{{ old('fulfilment_type', 'delivery') }}',
        selectedZoneId: '{{ old('delivery_zone_id', $deliveryZones->first()?->id ?? '') }}',
        paymentMethod: '{{ old('payment_method', 'paystack') }}',
        zones: {{ Js::from($deliveryZones) }},
        
        get selectedZone() {
            return this.zones.find(z => z.id == this.selectedZoneId) || null;
        },
        get deliveryFeeMinor() {
            if (this.fulfilmentType === 'pickup') return 0;
            return this.selectedZone ? parseInt(this.selectedZone.fee_minor) : 0;
        },
        get totalMinor() {
            return ($store.cart.subtotalMinor || 0) + this.deliveryFeeMinor;
        },
        formatMoney(minor) {
            return this.$store.currency.format(minor || 0);
        },
        allowPod() {
            if (this.fulfilmentType === 'pickup') return true;
            return this.selectedZone ? Boolean(this.selectedZone.allow_pay_on_delivery) : false;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-stone-500 mb-8">
                <a href="{{ route('home') }}" class="hover:text-[#0D4A2B]">Home</a>
                <span>/</span>
                <a href="{{ route('menu.index') }}" class="hover:text-[#0D4A2B]">Menu</a>
                <span>/</span>
                <span class="text-stone-900 font-bold">Checkout</span>
            </nav>

            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 mb-2">
                Order & Checkout
            </h1>
            <p class="text-stone-600 text-sm mb-10">
                Freshly prepared to order. Provide your delivery and contact details below.
            </p>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs">
                    <div class="flex">
                        <div class="shrink-0 text-red-500">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Please review the following errors:</h3>
                            <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Empty Cart Fallback -->
            <div x-show="$store.cart.items.length === 0" class="bg-white rounded-3xl p-12 text-center border border-stone-200 shadow-sm max-w-2xl mx-auto my-8">
                <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="font-serif text-2xl font-bold text-stone-900 mb-2">Your food tray is empty</h2>
                <p class="text-stone-500 text-sm mb-6">Select your favorite Nigerian dishes, combos, or catering packages before checking out.</p>
                <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 bg-[#0D4A2B] text-white px-6 py-3.5 rounded-full font-bold text-sm shadow hover:bg-[#09351e]">
                    Browse Dishes
                </a>
            </div>

            <!-- Main Checkout Grid -->
            <form 
                x-show="$store.cart.items.length > 0" 
                method="POST" 
                action="{{ route('checkout.store') }}" 
                class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start"
            >
                @csrf

                <!-- Hidden Cart Payload Input synchronized with Alpine -->
                <input type="hidden" name="cart_items" :value="JSON.stringify($store.cart.items)">
                <input type="hidden" name="currency" :value="$store.currency.selected">

                <!-- Left Column: Checkout Details Form (7 cols) -->
                <div class="lg:col-span-7 space-y-8">
                    <!-- 1. Customer Information -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-sm">
                        <h2 class="font-serif text-xl font-bold text-stone-900 mb-4 flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-[#0D4A2B] text-amber-400 flex items-center justify-center text-xs font-bold">1</span>
                            Customer Contact
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="customer_name" 
                                    id="customer_name" 
                                    value="{{ old('customer_name', auth()->user()?->name) }}"
                                    required 
                                    placeholder="e.g. Babatunde Adeleke"
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                >
                            </div>

                            <div>
                                <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    name="customer_email" 
                                    id="customer_email" 
                                    value="{{ old('customer_email', auth()->user()?->email) }}"
                                    required 
                                    placeholder="babatunde@example.com"
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                >
                            </div>

                            <div class="sm:col-span-2">
                                <label for="customer_phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Phone Number (WhatsApp Preferred) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    name="customer_phone" 
                                    id="customer_phone" 
                                    value="{{ old('customer_phone') }}"
                                    required 
                                    placeholder="+234 803 123 4567 or +44 7911..."
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                >
                                <p class="text-stone-400 text-[11px] mt-1">Our dispatch rider and kitchen will send live updates to this number.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Fulfillment Method (Delivery vs Pickup) -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-sm">
                        <h2 class="font-serif text-xl font-bold text-stone-900 mb-4 flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-[#0D4A2B] text-amber-400 flex items-center justify-center text-xs font-bold">2</span>
                            Fulfillment Method
                        </h2>

                        <!-- Toggle Pills -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <label 
                                class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 cursor-pointer transition-all text-center"
                                :class="fulfilmentType === 'delivery' ? 'border-[#0D4A2B] bg-emerald-50/40 text-[#0D4A2B] font-bold shadow-xs' : 'border-stone-200 hover:border-stone-300 text-stone-600'"
                            >
                                <input type="radio" name="fulfilment_type" value="delivery" class="sr-only" x-model="fulfilmentType">
                                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                                <span class="text-sm">Doorstep Delivery</span>
                                <span class="text-xs font-normal text-stone-500">Local or International Courier</span>
                            </label>

                            <label 
                                class="relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 cursor-pointer transition-all text-center"
                                :class="fulfilmentType === 'pickup' ? 'border-[#0D4A2B] bg-emerald-50/40 text-[#0D4A2B] font-bold shadow-xs' : 'border-stone-200 hover:border-stone-300 text-stone-600'"
                            >
                                <input type="radio" name="fulfilment_type" value="pickup" class="sr-only" x-model="fulfilmentType">
                                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-sm">Kitchen Pickup (Free)</span>
                                <span class="text-xs font-normal text-stone-500">Admiralty Way, Lekki Phase 1</span>
                            </label>
                        </div>

                        <!-- Delivery Specific Fields -->
                        <div x-show="fulfilmentType === 'delivery'" class="space-y-4">
                            <div>
                                <label for="delivery_zone_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Select Delivery Zone <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="delivery_zone_id" 
                                    id="delivery_zone_id" 
                                    x-model="selectedZoneId"
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                >
                                    @foreach($deliveryZones as $zone)
                                        <option value="{{ $zone->id }}" {{ old('delivery_zone_id') == $zone->id ? 'selected' : '' }}>
                                            {{ $zone->name }} ({{ $zone->formatted_fee }} • ~{{ $zone->lead_time_minutes >= 60 ? round($zone->lead_time_minutes / 60) . ' hrs' : $zone->lead_time_minutes . ' mins' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="delivery_address_street" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="delivery_address_street" 
                                    id="delivery_address_street" 
                                    value="{{ old('delivery_address_street') }}"
                                    placeholder="e.g. 14 Admiralty Way, Flat 3B"
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                >
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="delivery_address_city" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                        City / Area <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="delivery_address_city" 
                                        id="delivery_address_city" 
                                        value="{{ old('delivery_address_city', 'Lagos') }}"
                                        placeholder="Lekki Phase 1 / London"
                                        class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                    >
                                </div>
                                <div>
                                    <label for="delivery_address_state" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                        State / Country
                                    </label>
                                    <input 
                                        type="text" 
                                        name="delivery_address_state" 
                                        id="delivery_address_state" 
                                        value="{{ old('delivery_address_state', 'Lagos State, Nigeria') }}"
                                        placeholder="Lagos / Greater London / Georgia"
                                        class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="delivery_instructions" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                    Rider Instructions (Optional)
                                </label>
                                <input 
                                    type="text" 
                                    name="delivery_instructions" 
                                    id="delivery_instructions" 
                                    value="{{ old('delivery_instructions') }}"
                                    placeholder="e.g. Gate code #4512, call when outside the estate"
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                                >
                            </div>
                        </div>

                        <!-- Pickup Specific Notice -->
                        <div x-show="fulfilmentType === 'pickup'" class="p-4 rounded-2xl bg-amber-50 border border-amber-200/70 text-xs text-stone-700 space-y-1">
                            <strong class="font-bold text-amber-950 block">Pickup Location:</strong>
                            <p>Nigerian Kitchen Central Base, Admiralty Way, Lekki Phase 1, Lagos.</p>
                            <p class="text-stone-500">Your meal will be packaged in thermal containers ready for pickup within 35–45 minutes of confirmation.</p>
                        </div>
                    </div>

                    <!-- 3. Payment Method -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-sm">
                        <h2 class="font-serif text-xl font-bold text-stone-900 mb-4 flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-[#0D4A2B] text-amber-400 flex items-center justify-center text-xs font-bold">3</span>
                            Select Payment Method
                        </h2>

                        <div class="space-y-3">
                            <!-- Paystack -->
                            <label 
                                class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                :class="paymentMethod === 'paystack' ? 'border-[#0D4A2B] bg-emerald-50/40' : 'border-stone-200 hover:border-stone-300'"
                            >
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="paystack" class="text-[#0D4A2B] focus:ring-[#0D4A2B]" x-model="paymentMethod">
                                    <div>
                                        <span class="font-bold text-sm text-stone-900 block">Paystack (Cards, USSD, Bank Transfer)</span>
                                        <span class="text-xs text-stone-500">Fast, instant authorization in Nigerian Naira (₦)</span>
                                    </div>
                                </div>
                                <span class="text-xs font-extrabold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-md">NGN</span>
                            </label>

                            <!-- Stripe -->
                            <label 
                                class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                :class="paymentMethod === 'stripe' ? 'border-[#0D4A2B] bg-emerald-50/40' : 'border-stone-200 hover:border-stone-300'"
                            >
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="stripe" class="text-[#0D4A2B] focus:ring-[#0D4A2B]" x-model="paymentMethod">
                                    <div>
                                        <span class="font-bold text-sm text-stone-900 block">Stripe International (Diaspora Cards)</span>
                                        <span class="text-xs text-stone-500">Debit / Credit cards in GBP (£), USD ($), CAD ($), EUR (€)</span>
                                    </div>
                                </div>
                                <span class="text-xs font-extrabold text-blue-800 bg-blue-100 px-2.5 py-1 rounded-md">Diaspora</span>
                            </label>

                            <!-- Direct Bank Transfer -->
                            <label 
                                class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                :class="paymentMethod === 'bank_transfer' ? 'border-[#0D4A2B] bg-emerald-50/40' : 'border-stone-200 hover:border-stone-300'"
                            >
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="text-[#0D4A2B] focus:ring-[#0D4A2B]" x-model="paymentMethod">
                                    <div>
                                        <span class="font-bold text-sm text-stone-900 block">Direct Bank Transfer (GTBank / Zenith)</span>
                                        <span class="text-xs text-stone-500">Transfer directly with reference and upload receipt via WhatsApp</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-stone-600 bg-stone-100 px-2 py-1 rounded">Manual</span>
                            </label>

                            <!-- Pay on Delivery / POS -->
                            <div x-show="allowPod()">
                                <label 
                                    class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all"
                                    :class="paymentMethod === 'pay_on_delivery' ? 'border-[#0D4A2B] bg-emerald-50/40' : 'border-stone-200 hover:border-stone-300'"
                                >
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="pay_on_delivery" class="text-[#0D4A2B] focus:ring-[#0D4A2B]" x-model="paymentMethod">
                                        <div>
                                            <span class="font-bold text-sm text-stone-900 block">Pay on Delivery / Pickup (Cash or POS)</span>
                                            <span class="text-xs text-stone-500">Hand card to rider upon delivery</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-amber-800 bg-amber-100 px-2 py-1 rounded">Doorstep</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Notes & Instructions -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-sm">
                        <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Special Kitchen Instructions / Cooking Notes (Optional)
                        </label>
                        <textarea 
                            name="notes" 
                            id="notes" 
                            rows="2" 
                            placeholder="e.g. Please make the jollof extra spicy, pack stew separately, no crayfish..."
                            class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm p-3"
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Right Column: Food Tray Summary (5 cols) -->
                <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-md">
                        <div class="flex items-center justify-between border-b border-stone-200 pb-4 mb-4">
                            <h3 class="font-serif text-xl font-bold text-stone-900">Food Tray Summary</h3>
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full" x-text="$store.cart.count + ' items'"></span>
                        </div>

                        <!-- Item List -->
                        <div class="space-y-4 max-h-72 overflow-y-auto pr-1 mb-6">
                            <template x-for="(item, index) in $store.cart.items" :key="index">
                                <div class="flex items-center gap-3 py-2 border-b border-stone-100 last:border-0">
                                    <img :src="item.image" :alt="item.name" class="w-12 h-12 rounded-xl object-cover border border-stone-200 shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-stone-900 truncate" x-text="item.name"></h4>
                                        <p x-show="item.variant" class="text-[11px] text-amber-700" x-text="item.variant"></p>
                                        <span class="text-xs text-stone-500" x-text="'Qty: ' + item.quantity"></span>
                                    </div>
                                    <span class="text-xs font-bold text-stone-900 shrink-0" x-text="formatMoney(item.price * item.quantity)"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Financial Totals -->
                        <div class="space-y-2.5 border-t border-stone-200 pt-4 text-sm text-stone-600">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span class="font-semibold text-stone-900" x-text="formatMoney($store.cart.subtotalMinor)"></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="flex items-center gap-1">
                                    <span>Delivery Fee:</span>
                                    <span x-show="fulfilmentType === 'pickup'" class="text-[10px] text-emerald-700 font-bold">(Free Pickup)</span>
                                </span>
                                <span class="font-semibold text-stone-900" x-text="formatMoney(deliveryFeeMinor)"></span>
                            </div>

                            <div class="flex justify-between text-base font-bold text-stone-900 border-t border-stone-200 pt-3">
                                <span>Total Amount:</span>
                                <span class="text-xl font-serif font-extrabold text-[#0D4A2B]" x-text="formatMoney(totalMinor)"></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 pt-2">
                            <button 
                                type="submit" 
                                class="w-full bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white py-4 px-6 rounded-2xl font-bold text-base shadow-lg transition-all duration-150 transform active:scale-98 flex items-center justify-center gap-2"
                            >
                                <span>Place Order & Pay</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Guarantee Badges -->
                        <div class="mt-6 grid grid-cols-3 gap-2 text-center text-[10px] text-stone-500 border-t border-stone-100 pt-4">
                            <div>
                                <span class="block text-base mb-0.5">🔒</span>
                                <span class="font-medium">256-bit Encrypted</span>
                            </div>
                            <div>
                                <span class="block text-base mb-0.5">🍲</span>
                                <span class="font-medium">Fresh to Order</span>
                            </div>
                            <div>
                                <span class="block text-base mb-0.5">🚀</span>
                                <span class="font-medium">Thermal Pack</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

