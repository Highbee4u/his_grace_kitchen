<x-guest-layout>
    @section('title', 'Create your account | Nigerian Kitchen')

    <div class="mb-8">
        <span class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">Join the table</span>
        <h1 class="mt-2 font-serif text-4xl font-extrabold tracking-tight text-stone-900">Make every order feel easy.</h1>
        <p class="mt-3 text-sm leading-relaxed text-stone-500">Create an account to save addresses, follow orders, and reorder your favorite Nigerian dishes in a few taps.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Full name" class="mb-2 block text-sm font-bold text-stone-700" />
            <x-text-input id="name" class="block w-full rounded-xl border-stone-300 px-4 py-3 shadow-sm focus:border-[#0D4A2B] focus:ring-[#0D4A2B]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email address" class="mb-2 block text-sm font-bold text-stone-700" />
            <x-text-input id="email" class="block w-full rounded-xl border-stone-300 px-4 py-3 shadow-sm focus:border-[#0D4A2B] focus:ring-[#0D4A2B]" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" class="mb-2 block text-sm font-bold text-stone-700" />
            <x-text-input id="password" class="block w-full rounded-xl border-stone-300 px-4 py-3 shadow-sm focus:border-[#0D4A2B] focus:ring-[#0D4A2B]"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="At least 8 characters" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirm password" class="mb-2 block text-sm font-bold text-stone-700" />
            <x-text-input id="password_confirmation" class="block w-full rounded-xl border-stone-300 px-4 py-3 shadow-sm focus:border-[#0D4A2B] focus:ring-[#0D4A2B]"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4 pt-1">
            <a class="text-sm font-bold text-[#0D4A2B] hover:text-amber-700" href="{{ route('login') }}">Already registered?</a>
            <x-primary-button class="rounded-xl bg-[#0D4A2B] px-6 py-3 font-bold text-white shadow-lg shadow-emerald-900/15 hover:bg-[#09351e] focus:bg-[#09351e] active:bg-[#062416]">
                {{ __('Create account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
