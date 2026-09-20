@extends('layouts.storefront')

@section('title', 'Contact Us & Kitchen Locations | Nigerian Kitchen')
@section('meta_description', 'Get in touch with Nigerian Kitchen. Lagos central kitchen address, customer care WhatsApp, email, opening hours, and diaspora logistics inquiries.')

@push('json_ld')
    {!! \App\Support\SeoHelper::render(\App\Support\SeoHelper::breadcrumbSchema([
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Contact Us', 'url' => route('contact')],
    ])) !!}
@endpush

@section('content')
    <!-- Hero Banner -->
    <section class="relative bg-gradient-to-b from-stone-950 via-[#0D4A2B] to-stone-900 text-white py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#F59E0B_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 border border-amber-500/30 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                We're Here For You
            </span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mb-6">
                Get in Touch with <span class="text-amber-400">Our Kitchen</span>
            </h1>
            <p class="text-stone-300 text-base sm:text-lg max-w-2xl mx-auto font-light leading-relaxed">
                Have questions about daily delivery, event catering packages, dietary accommodations, or overseas diaspora shipments? Connect with us via WhatsApp, phone, or email.
            </p>
        </div>
    </section>

    <!-- Main Contact Information & Map -->
    <section class="py-16 sm:py-20 bg-[#FCFBF7]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Contact Card 1: Direct WhatsApp & Phone -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/80 shadow-sm text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-stone-900 mb-2">Instant WhatsApp Care</h3>
                    <p class="text-stone-500 text-xs sm:text-sm mb-6 leading-relaxed">
                        Fastest way to order, modify an active order, or enquire about today's fresh soup pot.
                    </p>
                    <a 
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber ?? '2348000000000') }}?text={{ urlencode('Hello Nigerian Kitchen! I have an inquiry.') }}"
                        target="_blank"
                        rel="noopener noreferrer" 
                        class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl text-sm shadow transition-colors"
                    >
                        Chat on WhatsApp
                    </a>
                </div>

                <!-- Contact Card 2: Email & Business Enquiries -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/80 shadow-sm text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-stone-900 mb-2">Email Desk</h3>
                    <p class="text-stone-500 text-xs sm:text-sm mb-6 leading-relaxed">
                        For corporate accounts, invoice questions, supplier partnerships, and event contracts.
                    </p>
                    <a 
                        href="mailto:orders@nigeriankitchen.test" 
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#0D4A2B] hover:bg-[#09351e] text-white font-bold py-3 px-4 rounded-xl text-sm shadow transition-colors"
                    >
                        hello@example.com
                    </a>
                </div>

                <!-- Contact Card 3: Kitchen Base & Operating Hours -->
                <div class="bg-white rounded-3xl p-8 border border-stone-200/80 shadow-sm text-center flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-800 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-stone-900 mb-2">Central Kitchen Base</h3>
                    <p class="text-stone-500 text-xs sm:text-sm mb-6 leading-relaxed">
                        Admiralty Way, Lekki Phase 1, Lagos, Nigeria.<br>
                        <strong class="text-stone-700 block mt-1">Kitchen Hours:</strong>
                        Mon – Sun: 10:00 AM – 10:00 PM (WAT)
                    </p>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Kitchen Currently Active
                    </span>
                </div>
            </div>

            <!-- Delivery Zone Coverage Card -->
            <div class="mt-12 bg-white rounded-3xl p-8 sm:p-10 border border-stone-200/80 shadow-md">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <span class="text-amber-600 font-bold uppercase tracking-widest text-xs">Coverage Overview</span>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">
                            Where We Deliver
                        </h2>
                        <p class="text-stone-600 text-sm mt-2 max-w-xl">
                            Local riders cover all Lagos zones with thermal insulated bags. International express cargo covers major metro centers in the United Kingdom, United States, Canada, and Western Europe.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('menu.index') }}" class="bg-[#0D4A2B] text-white hover:bg-[#09351e] px-6 py-3 rounded-xl font-bold text-sm shadow transition-all">
                            Order for Delivery
                        </a>
                        <a href="{{ route('catering.index') }}" class="bg-amber-500 text-stone-950 hover:bg-amber-600 px-6 py-3 rounded-xl font-bold text-sm shadow transition-all">
                            Book Event Catering
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

