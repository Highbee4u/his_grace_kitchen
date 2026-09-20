@extends('layouts.storefront')

@section('title', 'Authentic Nigerian Event Catering & Diaspora Banquets | Nigerian Kitchen')
@section('meta_description', 'Full-service Nigerian event catering for weddings, corporate galas, and family gatherings. Featuring live Suya BBQ stations, rich soup bars, and international chilled air-cargo banquets.')

@push('json_ld')
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::breadcrumbSchema([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Event Catering', 'url' => route('catering.index')],
    ])) !!}
@endpush

@section('content')
    <!-- Catering Hero -->
    <section class="relative bg-gradient-to-b from-stone-950 via-[#0D4A2B] to-stone-900 text-white py-20 sm:py-28 overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#F59E0B_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 border border-amber-500/30 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                From 15 to 1,000+ Guests • Local & Diaspora
            </span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mb-6">
                Extraordinary Nigerian <span class="text-amber-400">Event Catering</span>
            </h1>
            <p class="text-stone-300 text-base sm:text-xl max-w-3xl mx-auto font-light leading-relaxed mb-8">
                From unforgettable Owambe weddings and high-profile corporate galas to blast-chilled international banquet boxes delivered right to your overseas celebration.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="#quote-form" class="bg-amber-500 hover:bg-amber-600 text-stone-950 px-8 py-4 rounded-full font-bold text-sm shadow-xl transition-all transform hover:scale-105">
                    Request an Event Quote
                </a>
                <a href="#packages" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-4 rounded-full font-semibold text-sm transition-all">
                    View Packages Below
                </a>
            </div>
        </div>
    </section>

    <!-- Value Propositions -->
    <section class="py-12 bg-white border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                    <div class="w-12 h-12 rounded-full bg-[#0D4A2B] text-amber-400 flex items-center justify-center mx-auto mb-3 font-serif font-bold text-lg">
                        🔥
                    </div>
                    <h4 class="font-bold text-stone-900 text-sm">Live Suya BBQ Station</h4>
                    <p class="text-stone-500 text-xs mt-1">Sizzling charcoal skewers carved fresh before your guests.</p>
                </div>
                <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                    <div class="w-12 h-12 rounded-full bg-[#0D4A2B] text-amber-400 flex items-center justify-center mx-auto mb-3 font-serif font-bold text-lg">
                        🍲
                    </div>
                    <h4 class="font-bold text-stone-900 text-sm">Native Soup & Swallow Bar</h4>
                    <p class="text-stone-500 text-xs mt-1">Egusi, Efo Riro, and Ogbono paired with piping hot wraps.</p>
                </div>
                <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                    <div class="w-12 h-12 rounded-full bg-[#0D4A2B] text-amber-400 flex items-center justify-center mx-auto mb-3 font-serif font-bold text-lg">
                        👔
                    </div>
                    <h4 class="font-bold text-stone-900 text-sm">Uniformed White-Glove Staff</h4>
                    <p class="text-stone-500 text-xs mt-1">Professional servers, chafing setups, and banquet coordination.</p>
                </div>
                <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                    <div class="w-12 h-12 rounded-full bg-[#0D4A2B] text-amber-400 flex items-center justify-center mx-auto mb-3 font-serif font-bold text-lg">
                        ✈️
                    </div>
                    <h4 class="font-bold text-stone-900 text-sm">Diaspora Air-Cargo Packs</h4>
                    <p class="text-stone-500 text-xs mt-1">Blast-chilled vacuum containers dispatched to the UK, US & Canada.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Catering Packages Grid -->
    <section id="packages" class="py-20 bg-[#FCFBF7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-amber-600 font-bold uppercase tracking-widest text-xs">Curated Packages</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 mt-2">
                    Signature Event & Banquet Menus
                </h2>
                <p class="text-stone-600 text-sm sm:text-base mt-3">
                    Each package can be customised with supplementary live stations, small chop platters, and artisanal drinks.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
                @forelse($packages as $package)
                    <div class="bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-stone-200/80 flex flex-col group">
                        <!-- Image & Pricing Banner -->
                        <div class="relative h-64 sm:h-72 overflow-hidden bg-stone-100">
                            <img 
                                src="{{ $package->image }}" 
                                alt="{{ $package->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-950/85 via-stone-950/20 to-transparent"></div>

                            <div class="absolute bottom-5 left-6 right-6 flex items-end justify-between text-white">
                                <div>
                                    <span class="text-xs uppercase font-bold tracking-wider text-amber-400 block mb-1">
                                        {{ $package->pricing_model === 'per_head' ? 'Per Guest Estimate' : 'Fixed Package Total' }}
                                    </span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl sm:text-3xl font-serif font-extrabold" x-text="$store.currency.format({{ $package->price_minor }})">{{ $package->formatted_price }}</span>
                                        @if($package->pricing_model === 'per_head')
                                            <span class="text-xs text-stone-300 font-medium">/ guest</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="bg-[#0D4A2B] text-amber-300 text-xs font-extrabold px-3 py-1.5 rounded-full border border-amber-500/30">
                                    {{ $package->pricing_model === 'per_head' ? 'Per Head' : 'Banquet Box' }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-serif text-2xl font-bold text-stone-900 group-hover:text-[#0D4A2B] transition-colors mb-3">
                                    {{ $package->name }}
                                </h3>
                                <p class="text-stone-600 text-sm leading-relaxed mb-6">
                                    {{ $package->description }}
                                </p>

                                <!-- Included Offerings -->
                                @if(!empty($package->includes) && is_array($package->includes))
                                    <div class="bg-stone-50 border border-stone-200/80 rounded-2xl p-5 mb-6">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-stone-800 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Package Specifications & Inclusions:
                                        </h4>
                                        <ul class="space-y-2 text-xs font-medium text-stone-700">
                                            @foreach($package->includes as $inc)
                                                <li class="flex items-start gap-2.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-1.5 shrink-0"></span>
                                                    <span>{{ $inc }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <!-- Button selecting package in form -->
                            <div class="pt-4 border-t border-stone-100 flex items-center justify-between gap-4">
                                <a 
                                    href="#quote-form" 
                                    onclick="document.getElementById('catering_package_id').value = '{{ $package->id }}';"
                                    class="w-full text-center bg-[#0D4A2B] hover:bg-[#09351e] text-white py-3.5 px-4 rounded-xl font-bold text-sm shadow transition-all duration-150"
                                >
                                    Select This Package & Get Quote
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-16 bg-white rounded-3xl border border-stone-200">
                        <p class="text-stone-500">No catering packages currently published. Please contact us directly.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Interactive Catering Quote Request Form -->
    <section id="quote-form" class="py-20 bg-stone-100 border-t border-stone-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl border border-stone-200 overflow-hidden">
                <div class="bg-[#0D4A2B] text-white p-8 sm:p-10 text-center">
                    <span class="text-xs uppercase tracking-widest font-bold text-amber-400">Tailored Proposals</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold mt-1">Request Your Custom Catering Quote</h2>
                    <p class="text-emerald-100 text-sm sm:text-base mt-2 max-w-xl mx-auto">
                        Share details of your upcoming event or diaspora banquet. Our culinary event director will review your requirements and reply with a complete quotation within 24 hours.
                    </p>
                </div>

                <form method="POST" action="{{ route('catering.store') }}" class="p-8 sm:p-10 space-y-6">
                    @csrf
                    <input type="text" name="website_hp" style="display:none !important; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please correct the errors below:</h3>
                                    <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Customer Name -->
                        <div>
                            <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                id="customer_name" 
                                value="{{ old('customer_name', auth()->user()?->name) }}"
                                required 
                                placeholder="e.g. Chief Babatunde Adeleke"
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                            >
                        </div>

                        <!-- Customer Email -->
                        <div>
                            <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="customer_email" 
                                id="customer_email" 
                                value="{{ old('customer_email', auth()->user()?->email) }}"
                                required 
                                placeholder="babatunde@example.com"
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Phone Number -->
                        <div>
                            <label for="customer_phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                Phone Number (WhatsApp Enabled) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                name="customer_phone" 
                                id="customer_phone" 
                                value="{{ old('customer_phone') }}"
                                required 
                                placeholder="+234 803 123 4567 or +44 7911..."
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                            >
                        </div>

                        <!-- Package Selection -->
                        <div>
                            <label for="catering_package_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                Preferred Package (Optional)
                            </label>
                            <select 
                                name="catering_package_id" 
                                id="catering_package_id" 
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                            >
                                <option value="">-- Custom / Tailored Menu --</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}" {{ old('catering_package_id') == $pkg->id ? 'selected' : '' }}>
                                        {{ $pkg->name }} ({{ $pkg->formatted_price }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Guest Count -->
                        <div>
                            <label for="guest_count" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                Expected Number of Guests <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="guest_count" 
                                id="guest_count" 
                                min="5" 
                                max="5000"
                                value="{{ old('guest_count', 50) }}"
                                required 
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                            >
                        </div>

                        <!-- Event Date -->
                        <div>
                            <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                Date of Event <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="event_date" 
                                id="event_date" 
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                value="{{ old('event_date') }}"
                                required 
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                            >
                        </div>
                    </div>

                    <!-- Venue / Destination -->
                    <div>
                        <label for="venue" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Event Venue or Delivery Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="venue" 
                            id="venue" 
                            value="{{ old('venue') }}"
                            required 
                            placeholder="e.g. Landmark Centre, Water Corporation Drive, VI, Lagos (or London, UK address)"
                            class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                        >
                    </div>

                    <!-- Dietary & Special Notes -->
                    <div>
                        <label for="dietary_notes" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Special Requests, Allergen Constraints & Dietary Notes
                        </label>
                        <textarea 
                            name="dietary_notes" 
                            id="dietary_notes" 
                            rows="4" 
                            placeholder="Tell us about allergies (shellfish, nuts), dietary preferences (vegetarian, halal, gluten-free), custom dishes you want, or specific setup times."
                            class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm p-3"
                        >{{ old('dietary_notes') }}</textarea>
                    </div>

                    <!-- Form Submission -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white py-4 px-6 rounded-2xl font-bold text-base shadow-lg transition-all duration-150 transform active:scale-98 flex items-center justify-center gap-2"
                        >
                            <span>Submit Quotation Request</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                        <p class="text-center text-xs text-stone-500 mt-3">
                            No immediate charge. Our team will verify kitchen capacity and dispatch an itemized quote to your email.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

