@extends('layouts.storefront')

@section('title', 'Track Order | Nigerian Kitchen')
@section('meta_description', 'Track your Nigerian Kitchen food delivery or catering order in real time.')

@section('content')
    <div class="bg-[#FCFBF7] py-16 sm:py-24">
        <div class="max-w-md mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-stone-200 shadow-md text-center">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                    🔍
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mb-2">
                    Track Your Food Order
                </h1>
                <p class="text-stone-500 text-xs sm:text-sm mb-6">
                    Enter your order reference and email address below to see the live preparation and delivery status.
                </p>

                @if(session('error'))
                    <div class="mb-6 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 font-medium text-left">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('orders.track') }}" class="space-y-4 text-left">
                    @csrf

                    <div>
                        <label for="order_number" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                            Order Number <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="order_number" 
                            id="order_number" 
                            value="{{ old('order_number') }}" 
                            required 
                            placeholder="e.g. NK-20260920-ABCD" 
                            class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3 uppercase font-mono"
                        >
                    </div>

                    <div>
                        <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                            Email Address Used at Checkout <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="customer_email" 
                            id="customer_email" 
                            value="{{ old('customer_email') }}" 
                            required 
                            placeholder="your-email@example.com" 
                            class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white py-3.5 px-4 rounded-xl font-bold text-sm shadow transition-all duration-150 transform active:scale-98 flex items-center justify-center gap-2 mt-2"
                    >
                        <span>Find My Order</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <div class="mt-8 pt-4 border-t border-stone-100 text-xs text-stone-500">
                    Need help? <a href="{{ route('contact') }}" class="text-[#0D4A2B] font-bold hover:underline">Contact our kitchen dispatch team</a>
                </div>
            </div>
        </div>
    </div>
@endsection

