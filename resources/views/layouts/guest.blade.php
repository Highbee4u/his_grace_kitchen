<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', ($siteName ?? config('app.name', 'Nigerian Kitchen')) . ' | Your kitchen, your way')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|playfair-display:600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-[#FCFBF7] font-sans text-stone-800 antialiased selection:bg-amber-500 selection:text-white">
        <main class="min-h-screen lg:grid lg:grid-cols-[minmax(0,1.05fr)_minmax(420px,0.95fr)]">
            <section class="relative hidden min-h-screen overflow-hidden bg-[#0D4A2B] px-10 py-10 text-white lg:flex lg:flex-col lg:justify-between xl:px-16">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_10%,rgba(245,158,11,.24),transparent_34%),linear-gradient(145deg,#0D4A2B,#062416)]"></div>
                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full border border-amber-400/40 bg-amber-500 text-lg font-extrabold text-stone-950 shadow-lg">{{ strtoupper(collect(explode(' ', $siteName ?? 'NK'))->map(fn ($word) => substr($word, 0, 1))->take(2)->join('')) }}</span>
                        <span>
                            <span class="block font-serif text-2xl font-bold">{{ $siteName ?? 'Nigerian Kitchen' }}</span>
                            <span class="block text-[10px] font-bold uppercase tracking-[0.24em] text-amber-300">Authentic native flavors</span>
                        </span>
                    </a>
                </div>
                <div class="relative z-10 max-w-xl pb-10">
                    <span class="mb-5 inline-flex rounded-full border border-amber-400/30 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-widest text-amber-200">From our kitchen to your table</span>
                    <h1 class="font-serif text-5xl font-extrabold leading-tight xl:text-6xl">Good food feels like coming home.</h1>
                    <p class="mt-6 max-w-lg text-base leading-relaxed text-emerald-50/75">Save your favorite dishes, track orders, and make your next Nigerian feast even easier.</p>
                    <div class="mt-8 flex flex-wrap gap-3 text-xs font-semibold text-emerald-50/80">
                        <span class="rounded-full bg-white/10 px-3 py-2">Freshly prepared</span>
                        <span class="rounded-full bg-white/10 px-3 py-2">Local delivery</span>
                        <span class="rounded-full bg-white/10 px-3 py-2">Diaspora friendly</span>
                    </div>
                </div>
            </section>

            <section class="flex min-h-screen flex-col px-5 py-6 sm:px-8 sm:py-10 lg:px-14 xl:px-20">
                <div class="flex items-center justify-between lg:justify-end">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#0D4A2B] lg:hidden">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#0D4A2B] text-xs font-extrabold text-amber-400">NK</span>
                        {{ $siteName ?? 'Nigerian Kitchen' }}
                    </a>
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-stone-500 transition-colors hover:text-[#0D4A2B]">Back to shop</a>
                </div>
                <div class="flex flex-1 items-center justify-center py-10">
                    <div class="w-full max-w-md">
                        {{ $slot }}
                    </div>
                </div>
                <p class="text-center text-xs text-stone-400">Fresh food, familiar flavors, always made with care.</p>
            </section>
        </div>
    </body>
</html>
