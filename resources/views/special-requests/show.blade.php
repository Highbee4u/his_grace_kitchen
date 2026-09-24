@extends('layouts.storefront')

@section('title', "Custom Dish Quote #{$specialRequest->reference} | His Grace Kitchen LTD")

@section('content')
    <div class="bg-[#FCFBF7] py-12 sm:py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/80 shadow-md">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-stone-100 pb-6 mb-6">
                    <div>
                        <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-900 border border-amber-300 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                            {{ ucfirst(str_replace('_', ' ', $specialRequest->status)) }}
                        </span>
                        <h1 class="font-serif text-3xl font-bold text-stone-900">
                            Custom Dish Request #{{ $specialRequest->reference }}
                        </h1>
                        <p class="text-xs text-stone-500 mt-1">
                            Requested by <strong class="text-stone-700">{{ $specialRequest->customer_name }}</strong> • Portions: <strong class="text-stone-700">{{ $specialRequest->quantity }}</strong>
                        </p>
                    </div>

                    <a href="{{ route('special-requests.create') }}" class="text-xs font-bold text-[#0D4A2B] hover:underline">
                        + New Request
                    </a>
                </div>

                <!-- Dish Description Card -->
                <div class="bg-stone-50 rounded-2xl p-6 border border-stone-200/80 mb-8 space-y-3">
                    <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block">Requested Traditional Delicacy:</span>
                    <p class="text-sm font-serif font-bold text-stone-900 leading-relaxed">
                        {{ $specialRequest->description }}
                    </p>

                    <div class="pt-2 grid grid-cols-2 gap-4 text-xs text-stone-600 border-t border-stone-200/60">
                        <div>
                            <span class="text-stone-400 block">Needed By:</span>
                            <strong>{{ $specialRequest->needed_by ? \Carbon\Carbon::parse($specialRequest->needed_by)->format('M d, Y') : 'Flexible' }}</strong>
                        </div>
                        <div>
                            <span class="text-stone-400 block">Indicated Budget:</span>
                            <strong>{{ $specialRequest->budget_minor ? \App\Support\Money::format($specialRequest->budget_minor, $specialRequest->currency) : 'Open to Quote' }}</strong>
                        </div>
                    </div>

                    @if($specialRequest->reference_image_url)
                        <div class="pt-3">
                            <span class="text-stone-400 block text-xs mb-1.5">Reference Image:</span>
                            <img src="{{ $specialRequest->reference_image_url }}" alt="Reference Dish" class="h-40 w-auto rounded-xl object-cover border border-stone-300">
                        </div>
                    @endif
                </div>

                <!-- Chef Quotation Section -->
                @if($specialRequest->quoted_total_minor)
                    <div class="border-2 border-emerald-600/30 bg-emerald-50/40 rounded-3xl p-6 sm:p-8 mb-8">
                        <div class="text-center pb-6 border-b border-stone-200 mb-6">
                            <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block mb-1">Chef Sourcing & Cooking Quote</span>
                            <span class="font-serif text-3xl sm:text-4xl font-extrabold text-[#0D4A2B] block">
                                {{ \App\Support\Money::format($specialRequest->quoted_total_minor, $specialRequest->currency) }}
                            </span>
                        </div>

                        @if($specialRequest->admin_notes)
                            <div class="mb-6 p-4 rounded-xl bg-white border border-stone-200 text-xs text-stone-700">
                                <strong class="text-stone-900 font-bold block mb-1">Head Chef's Cooking Notes:</strong>
                                <p class="leading-relaxed">{{ $specialRequest->admin_notes }}</p>
                            </div>
                        @endif

                        <!-- Payment Acceptance -->
                        @if(in_array($specialRequest->status, ['quote_sent', 'submitted']))
                            <form method="POST" action="{{ route('special-requests.accept', $specialRequest->reference) }}" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                        Choose Payment Method to Confirm Order:
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <label class="flex items-center gap-2 p-3 rounded-xl border border-stone-300 bg-white cursor-pointer hover:border-[#0D4A2B]">
                                            <input type="radio" name="payment_method" value="paystack" checked class="text-[#0D4A2B] focus:ring-[#0D4A2B]">
                                            <span class="text-xs font-bold text-stone-900">Paystack (NGN)</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-3 rounded-xl border border-stone-300 bg-white cursor-pointer hover:border-[#0D4A2B]">
                                            <input type="radio" name="payment_method" value="stripe" class="text-[#0D4A2B] focus:ring-[#0D4A2B]">
                                            <span class="text-xs font-bold text-stone-900">Stripe Diaspora</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-3 rounded-xl border border-stone-300 bg-white cursor-pointer hover:border-[#0D4A2B]">
                                            <input type="radio" name="payment_method" value="bank_transfer" class="text-[#0D4A2B] focus:ring-[#0D4A2B]">
                                            <span class="text-xs font-bold text-stone-900">Bank Transfer</span>
                                        </label>
                                    </div>
                                </div>

                                <button 
                                    type="submit" 
                                    class="w-full bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white py-4 px-6 rounded-2xl font-bold text-base shadow-lg transition-all duration-150 transform active:scale-98 flex items-center justify-center gap-2"
                                >
                                    <span>Accept Quote & Authorize Cooking</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </form>
                        @elseif($specialRequest->status === 'in_preparation')
                            <div class="p-4 rounded-2xl bg-emerald-100 text-emerald-900 text-xs font-medium text-center">
                                ✓ Payment verified! Our kitchen team has procured fresh native ingredients and is actively simmering your dish.
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-8 rounded-3xl bg-amber-50/70 border border-amber-200 text-center mb-8">
                        <h3 class="font-serif text-lg font-bold text-stone-900 mb-1">Checking Native Ingredients</h3>
                        <p class="text-stone-600 text-xs max-w-md mx-auto">
                            Our head chef is verifying market availability for fresh native herbs, spices, and proteins. We will dispatch your quotation link within 24 hours.
                        </p>
                    </div>
                @endif

                <!-- Footer WhatsApp Direct Link -->
                <div class="pt-6 border-t border-stone-200 flex items-center justify-between text-xs">
                    <a 
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber ?? '447988575682') }}?text={{ urlencode('Hello! I would like an update on my custom dish request #' . $specialRequest->reference) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-emerald-800 font-bold hover:underline"
                    >
                        💬 Chat with Head Chef on WhatsApp
                    </a>

                    <a href="{{ route('home') }}" class="text-stone-500 hover:text-stone-900">
                        ← Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

