<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', ($siteName ?? 'His Grace Kitchen LTD') . ' | Authentic Nigerian Cuisine & Diaspora Delivery')</title>
    <meta name="description" content="@yield('meta_description', 'Handcrafted Nigerian culinary classics: Smoky Party Jollof, Egusi Soup, Tender Flame-Grilled Suya, and Owambe Event Catering. Fast delivery across Nigeria and express courier to the UK, US, Canada, and Europe.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph & Social -->
    <meta property="og:site_name" content="{{ $siteName ?? 'His Grace Kitchen LTD' }}">
    <meta property="og:title" content="@yield('title', ($siteName ?? 'His Grace Kitchen LTD') . ' | Authentic Nigerian Cuisine')">
    <meta property="og:description" content="@yield('meta_description', 'Smoky Party Jollof, Rich Native Soups, and Authentic Grills delivered hot to your doorstep.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/hero-jollof.jpg'))">
    <meta property="og:locale" content="en_NG">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', ($siteName ?? 'His Grace Kitchen LTD') . ' | Authentic Nigerian Cuisine')">
    <meta name="twitter:description" content="@yield('meta_description', 'Smoky Party Jollof, Rich Native Soups, and Authentic Grills delivered hot to your doorstep.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/hero-jollof.jpg'))">

    <!-- Structured Data (JSON-LD) -->
    @stack('json_ld')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|playfair-display:600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-stone-800 bg-[#FCFBF7] min-h-screen flex flex-col selection:bg-amber-500 selection:text-white" x-data>

    <!-- Top Announcement Bar (Diaspora Notice & Currency Switcher) -->
    <div class="bg-gradient-to-r from-[#0D4A2B] via-[#125834] to-[#0D4A2B] text-amber-100 text-xs py-2 px-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2 font-medium">
                <span class="inline-flex items-center justify-center bg-amber-500 text-stone-950 font-bold px-1.5 py-0.5 rounded text-[10px] tracking-wide uppercase">
                    Diaspora
                </span>
                <span>Express courier shipping to the <strong>UK, USA, Canada & Europe</strong> • Fresh local delivery daily</span>
            </div>

            <div class="flex items-center gap-4 text-stone-300 text-xs">
                <span class="hidden md:inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Kitchen Open: Mon–Sun 10:00 AM – 10:00 PM
                </span>

                <div class="flex items-center gap-1.5 bg-black/25 px-2.5 py-1 rounded-full border border-white/10" x-data>
                    <span class="text-amber-400 font-bold">Currency:</span>
                    <select 
                        class="bg-transparent text-amber-200 border-none p-0 text-xs focus:ring-0 cursor-pointer font-semibold"
                        x-model="$store.currency.selected"
                        @change="$store.currency.setCurrency($event.target.value)"
                    >
                        <option value="NGN" class="bg-stone-900 text-white">NGN (₦)</option>
                        <option value="GBP" class="bg-stone-900 text-white">GBP (£)</option>
                        <option value="USD" class="bg-stone-900 text-white">USD ($)</option>
                        <option value="CAD" class="bg-stone-900 text-white">CAD ($)</option>
                        <option value="EUR" class="bg-stone-900 text-white">EUR (€)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-stone-200/80 shadow-xs" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#0D4A2B] flex items-center justify-center text-amber-400 shadow-md group-hover:scale-105 transition-transform duration-200 shrink-0">
                        <span class="font-serif text-base sm:text-lg font-extrabold tracking-tighter">
                            {{ strtoupper(collect(explode(' ', $siteName ?? 'HG'))->map(fn($w) => substr($w, 0, 1))->take(2)->join('')) }}
                        </span>
                    </div>
                    <div class="shrink-0">
                        <span class="font-serif text-xl sm:text-2xl font-bold tracking-tight text-stone-900 block leading-tight whitespace-nowrap">
                            {{ $siteName ?? 'His Grace Kitchen LTD' }}
                        </span>
                        <span class="text-[9px] sm:text-[10px] uppercase font-bold tracking-widest text-[#0D4A2B] block whitespace-nowrap">
                            Authentic Native Flavors
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links (Non-wrapping, streamlined) -->
                <nav class="hidden lg:flex items-center gap-4 xl:gap-7 text-sm font-semibold text-stone-700 whitespace-nowrap shrink-0">
                    <a href="{{ route('home') }}" class="hover:text-[#0D4A2B] transition-colors {{ request()->routeIs('home') ? 'text-[#0D4A2B] border-b-2 border-[#0D4A2B] pb-1' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('menu.index') }}" class="hover:text-[#0D4A2B] transition-colors {{ request()->routeIs('menu.*') ? 'text-[#0D4A2B] border-b-2 border-[#0D4A2B] pb-1' : '' }}">
                        Menu
                    </a>
                    <a href="{{ route('combos.index') }}" class="hover:text-[#0D4A2B] transition-colors {{ request()->routeIs('combos.*') ? 'text-[#0D4A2B] border-b-2 border-[#0D4A2B] pb-1' : '' }}">
                        Combos
                    </a>
                    <a href="{{ route('catering.index') }}" class="hover:text-[#0D4A2B] transition-colors {{ request()->routeIs('catering.*') ? 'text-[#0D4A2B] border-b-2 border-[#0D4A2B] pb-1' : '' }}">
                        Catering
                    </a>
                    <a href="{{ route('special-requests.create') }}" class="hover:text-[#0D4A2B] transition-colors {{ request()->routeIs('special-requests.*') ? 'text-[#0D4A2B] border-b-2 border-[#0D4A2B] pb-1' : '' }}">
                        Special Requests
                    </a>
                    <a href="{{ route('about') }}" class="hover:text-[#0D4A2B] transition-colors {{ request()->routeIs('about') ? 'text-[#0D4A2B] border-b-2 border-[#0D4A2B] pb-1' : '' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}" class="hover:text-[#0D4A2B] transition-colors {{ request()->routeIs('contact') ? 'text-[#0D4A2B] border-b-2 border-[#0D4A2B] pb-1' : '' }}">
                        Contact
                    </a>
                </nav>

                <!-- Actions (Account & Cart & Mobile Hamburger) -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold text-stone-700 bg-stone-100 hover:bg-stone-200 px-3 py-2 rounded-lg transition-colors whitespace-nowrap">
                            <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            My Account
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-xs font-semibold text-stone-700 hover:text-[#0D4A2B] px-3 py-2 whitespace-nowrap">
                            Log in
                        </a>
                    @endauth

                    <!-- Slide-Over Cart Trigger Button -->
                    <button 
                        type="button" 
                        @click="$store.cart.isOpen = true"
                        class="relative inline-flex items-center gap-2 bg-[#0D4A2B] hover:bg-[#09351e] text-white px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-full font-semibold text-sm shadow-sm transition-all duration-150 transform active:scale-95 whitespace-nowrap shrink-0"
                    >
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="hidden sm:inline">My Tray</span>
                        <span 
                            class="bg-amber-500 text-stone-950 text-xs font-extrabold px-2 py-0.5 rounded-full min-w-[20px] text-center"
                            x-text="$store.cart.count"
                        >0</span>
                    </button>

                    <!-- Mobile Hamburger Toggle Button -->
                    <button 
                        type="button" 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 rounded-xl text-stone-700 hover:text-[#0D4A2B] hover:bg-stone-100 transition-colors"
                        aria-label="Toggle Navigation Menu"
                    >
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-cloak x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div 
            x-cloak 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden border-t border-stone-200 bg-white px-4 py-3 space-y-1 shadow-md"
            @click.outside="mobileMenuOpen = false"
        >
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('home') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Home
            </a>
            <a href="{{ route('menu.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('menu.*') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Menu
            </a>
            <a href="{{ route('combos.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('combos.*') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Combos & Deals
            </a>
            <a href="{{ route('catering.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('catering.*') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Event Catering
            </a>
            <a href="{{ route('special-requests.create') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('special-requests.*') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Special Requests
            </a>
            <a href="{{ route('reviews.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('reviews.*') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Customer Reviews
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('about') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Our Story
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('contact') ? 'bg-emerald-50 text-[#0D4A2B]' : 'text-stone-700 hover:bg-stone-50' }}">
                Contact Us
            </a>
            <div class="pt-2 border-t border-stone-100 flex items-center justify-between">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#0D4A2B] py-2 px-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Customer Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#0D4A2B] py-2 px-3">
                        Log in / Register
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Success & Alert Notifications -->
    @if (session('success'))
        <div class="bg-emerald-700 text-white px-4 py-3 shadow-md" role="alert">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Body Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Slide-Over Shopping Cart Drawer (Alpine.js) -->
    <div 
        x-cloak 
        x-show="$store.cart.isOpen" 
        class="relative z-50" 
        aria-labelledby="slide-over-title" 
        role="dialog" 
        aria-modal="true"
    >
        <!-- Background Backdrop -->
        <div 
            x-show="$store.cart.isOpen"
            x-transition:enter="ease-in-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"
            @click="$store.cart.isOpen = false"
        ></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div 
                        x-show="$store.cart.isOpen"
                        x-transition:enter="transform transition ease-in-out duration-300"
                        x-transition:enter-start="translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-300"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full"
                        class="pointer-events-auto w-screen max-w-md"
                    >
                        <div class="flex h-full flex-col bg-white shadow-2xl">
                            <!-- Drawer Header -->
                            <div class="flex items-center justify-between px-6 py-5 border-b border-stone-200 bg-[#0D4A2B] text-white">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <h2 class="text-lg font-bold font-serif">Your Food Tray</h2>
                                </div>
                                <button 
                                    type="button" 
                                    @click="$store.cart.isOpen = false"
                                    class="text-stone-300 hover:text-white p-1 rounded-md"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Cart Items List -->
                            <div class="flex-1 overflow-y-auto p-6 space-y-4">
                                <template x-if="$store.cart.items.length === 0">
                                    <div class="text-center py-16 space-y-4">
                                        <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="font-serif text-lg font-bold text-stone-800">Your food tray is empty</h3>
                                        <p class="text-stone-500 text-sm max-w-xs mx-auto">Explore our hot jollof, slow-cooked soups, and flame-grilled suya dishes to fill your tray.</p>
                                        <a 
                                            href="{{ route('menu.index') }}" 
                                            @click="$store.cart.isOpen = false"
                                            class="inline-block bg-[#0D4A2B] text-white px-5 py-2.5 rounded-full text-sm font-semibold shadow-sm hover:bg-[#09351e]"
                                        >
                                            Explore Menu
                                        </a>
                                    </div>
                                </template>

                                <template x-for="(item, index) in $store.cart.items" :key="index">
                                    <div class="flex items-center gap-4 py-3 border-b border-stone-100">
                                        <img :src="item.image" :alt="item.name" class="w-16 h-16 rounded-xl object-cover shrink-0 border border-stone-200">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-stone-900 text-sm truncate" x-text="item.name"></h4>
                                            <p x-show="item.variant" class="text-xs text-amber-700 font-medium" x-text="item.variant"></p>
                                            <p class="text-xs font-bold text-stone-800 mt-1" x-text="$store.currency.format(item.price * item.quantity)"></p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button 
                                                type="button" 
                                                @click="$store.cart.changeQty(index, -1)" 
                                                class="w-7 h-7 rounded-md bg-stone-100 text-stone-700 font-bold hover:bg-stone-200 flex items-center justify-center text-sm"
                                            >−</button>
                                            <span class="text-sm font-bold w-5 text-center" x-text="item.quantity"></span>
                                            <button 
                                                type="button" 
                                                @click="$store.cart.changeQty(index, 1)" 
                                                class="w-7 h-7 rounded-md bg-stone-100 text-stone-700 font-bold hover:bg-stone-200 flex items-center justify-center text-sm"
                                            >+</button>
                                            <button 
                                                type="button" 
                                                @click="$store.cart.removeItem(index)" 
                                                class="text-stone-400 hover:text-red-600 ml-1"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Drawer Footer & Checkout -->
                            <div x-show="$store.cart.items.length > 0" class="border-t border-stone-200 p-6 bg-stone-50 space-y-4">
                                <div class="flex items-center justify-between text-base font-bold text-stone-900">
                                    <span>Subtotal:</span>
                                    <span class="text-emerald-800" x-text="$store.cart.formattedSubtotal"></span>
                                </div>
                                <p class="text-xs text-stone-500">Taxes and delivery fees calculated during checkout according to delivery zone.</p>
                                <div class="space-y-2">
                                    <a 
                                        href="{{ route('checkout.index') }}" 
                                        @click="$store.cart.isOpen = false;"
                                        class="w-full flex items-center justify-center gap-2 bg-[#0D4A2B] hover:bg-[#09351e] text-white py-3.5 px-4 rounded-xl font-bold shadow-md transition-all text-sm"
                                    >
                                        Proceed to Checkout
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                    <button 
                                        type="button" 
                                        @click="$store.cart.clear()" 
                                        class="w-full text-center text-xs text-stone-500 hover:text-red-600 py-1"
                                    >
                                        Clear Food Tray
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating WhatsApp Order Button -->
    <a 
        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber ?? '2348000000000') }}?text={{ urlencode('Hello His Grace Kitchen LTD! I would like to place a food order or catering enquiry.') }}" 
        target="_blank" 
        rel="noopener noreferrer"
        class="fixed bottom-6 right-6 z-30 flex items-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-full shadow-xl hover:shadow-2xl transition-all transform hover:scale-105"
        title="Order directly on WhatsApp"
    >
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
        <span class="font-bold text-sm tracking-wide">WhatsApp Order</span>
    </a>

    <!-- Footer -->
    <footer class="bg-stone-900 text-stone-300 pt-16 pb-12 border-t-4 border-amber-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-stone-800">
                <!-- Brand Overview -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#0D4A2B] flex items-center justify-center text-amber-400 font-serif text-base font-extrabold shadow-inner border border-white/10">
                            {{ strtoupper(collect(explode(' ', $siteName ?? 'NK'))->map(fn($w) => substr($w, 0, 1))->take(2)->join('')) }}
                        </div>
                        <span class="font-serif text-2xl font-bold text-white tracking-tight">{{ $siteName ?? 'His Grace Kitchen LTD' }}</span>
                    </div>
                    <p class="text-stone-400 text-sm leading-relaxed max-w-sm">
                        Crafting authentic Nigerian gastronomic experiences with slow-simmered rich native soups, firewood-infused smoky jollof, and succulent charcoal grills. Serving food lovers locally and worldwide across the UK, USA, Canada, and Europe.
                    </p>
                    <div class="pt-2 text-xs text-amber-400/90 font-medium">
                        🇳🇬 Base Kitchen: Lagos, Nigeria • ✈️ International Air Cargo Express
                    </div>
                </div>

                <!-- Menu Links -->
                <div class="space-y-3">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider font-serif">Our Dishes</h3>
                    <ul class="space-y-2 text-sm text-stone-400">
                        <li><a href="{{ route('menu.index', ['category' => 'rice-grains']) }}" class="hover:text-amber-400 transition-colors">Smoky Party Jollof</a></li>
                        <li><a href="{{ route('menu.index', ['category' => 'soups-swallows']) }}" class="hover:text-amber-400 transition-colors">Egusi & Pounded Yam</a></li>
                        <li><a href="{{ route('menu.index', ['category' => 'grills-street-food']) }}" class="hover:text-amber-400 transition-colors">Beef Suya & Asun</a></li>
                        <li><a href="{{ route('menu.index', ['category' => 'small-chops-sides']) }}" class="hover:text-amber-400 transition-colors">Golden Puff-Puff & Dodo</a></li>
                        <li><a href="{{ route('combos.index') }}" class="hover:text-amber-400 transition-colors">Owambe Party Boxes</a></li>
                    </ul>
                </div>

                <!-- Event & Services -->
                <div class="space-y-3">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider font-serif">Catering & Diaspora</h3>
                    <ul class="space-y-2 text-sm text-stone-400">
                        <li><a href="{{ route('catering.index') }}" class="hover:text-amber-400 transition-colors">Wedding & Gala Catering</a></li>
                        <li><a href="{{ route('catering.index') }}" class="hover:text-amber-400 transition-colors">Corporate Executive Lunch</a></li>
                        <li><a href="{{ route('special-requests.create') }}" class="hover:text-amber-400 transition-colors">Custom Off-Menu Dishes</a></li>
                        <li><a href="{{ route('catering.index') }}" class="hover:text-amber-400 transition-colors">UK/US Chilled Express Packs</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-amber-400 transition-colors">Live Suya Grilling Station</a></li>
                    </ul>
                </div>

                <!-- Direct Contact & Admin -->
                <div class="space-y-3">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider font-serif">Connect</h3>
                    <ul class="space-y-2 text-sm text-stone-400">
                        <li>Email: <a href="mailto:{{ $contactEmail ?? 'orders@africankitchen.test' }}" class="text-amber-400 hover:underline">{{ $contactEmail ?? 'orders@africankitchen.test' }}</a></li>
                        <li>WhatsApp Orders: <span class="text-stone-300 font-semibold">{{ $whatsappNumber ?? '+234 800 000 0000' }}</span></li>
                        <li><a href="{{ route('reviews.index') }}" class="hover:text-amber-400 transition-colors">Customer Reviews & Ratings</a></li>
                        <li class="pt-2">
                            <a href="{{ url('/admin') }}" class="inline-flex items-center gap-1.5 text-xs bg-stone-800 hover:bg-stone-700 text-stone-300 px-3 py-1.5 rounded border border-stone-700">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Staff Admin Portal
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
                <p>&copy; {{ date('Y') }} {{ $siteName ?? 'His Grace Kitchen LTD' }}. All rights reserved. Crafted with authentic passion.</p>
                <div class="flex items-center gap-4">
                    <span>Paystack (NGN)</span>
                    <span>•</span>
                    <span>Stripe (GBP, USD, CAD, EUR)</span>
                    <span>•</span>
                    <span>Bank Transfer</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

