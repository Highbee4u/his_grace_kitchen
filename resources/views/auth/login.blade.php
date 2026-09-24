<x-guest-layout>
    @section('title', 'Welcome back | Nigerian Kitchen')

    <div class="mb-8">
        <span class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">Welcome back</span>
        <h1 class="mt-2 font-serif text-4xl font-extrabold tracking-tight text-stone-900">Come back to your favorites.</h1>
        <p class="mt-3 text-sm leading-relaxed text-stone-500">Log in to reorder the dishes you love and keep your delivery details ready.</p>
    </div>

    <x-auth-session-status class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email address" class="mb-2 block text-sm font-bold text-stone-700" />
            <x-text-input id="email" class="block w-full rounded-xl border-stone-300 px-4 py-3 shadow-sm focus:border-[#0D4A2B] focus:ring-[#0D4A2B]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <x-input-label for="password" value="Password" class="block text-sm font-bold text-stone-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-[#0D4A2B] hover:text-amber-700" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full rounded-xl border-stone-300 px-4 py-3 shadow-sm focus:border-[#0D4A2B] focus:ring-[#0D4A2B]"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Enter your password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4 pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-stone-500">
                <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-[#0D4A2B] shadow-sm focus:ring-[#0D4A2B]" name="remember">
                <span>{{ __('Keep me signed in') }}</span>
            </label>
            <x-primary-button class="rounded-xl bg-[#0D4A2B] px-6 py-3 font-bold text-white shadow-lg shadow-emerald-900/15 hover:bg-[#09351e] focus:bg-[#09351e] active:bg-[#062416]">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-8 border-t border-stone-200 pt-6 text-center text-sm text-stone-500">New to the kitchen? <a href="{{ route('register') }}" class="font-bold text-[#0D4A2B] hover:text-amber-700">Create your account</a></p>
</x-guest-layout>
