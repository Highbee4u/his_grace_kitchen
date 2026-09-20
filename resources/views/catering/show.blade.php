@extends('layouts.storefront')

@section('title', "Catering Quotation #{$cateringRequest->reference} | His Grace Kitchen LTD")

@section('content')
    <div class="bg-[#FCFBF7] py-12 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/80 shadow-md">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-stone-100 pb-6 mb-6">
                    <div>
                        @php
                            $statusBadge = match($cateringRequest->status) {
                                'submitted' => ['label' => 'Under Chef Review', 'color' => 'bg-amber-100 text-amber-900 border-amber-300'],
                                'under_review' => ['label' => 'Curating Menu & Staff', 'color' => 'bg-amber-100 text-amber-900 border-amber-300'],
                                'quote_sent' => ['label' => 'Quotation Ready for Approval', 'color' => 'bg-blue-100 text-blue-900 border-blue-300'],
                                'accepted' => ['label' => 'Quotation Accepted', 'color' => 'bg-emerald-100 text-emerald-900 border-emerald-300'],
                                'deposit_paid' => ['label' => 'Date Locked (Deposit Paid)', 'color' => 'bg-emerald-100 text-emerald-900 border-emerald-300'],
                                'in_preparation' => ['label' => 'Kitchen In Preparation', 'color' => 'bg-purple-100 text-purple-900 border-purple-300'],
                                'completed' => ['label' => 'Event Completed', 'color' => 'bg-stone-100 text-stone-900 border-stone-300'],
                                default => ['label' => ucfirst($cateringRequest->status), 'color' => 'bg-stone-100 text-stone-800 border-stone-300']
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 {{ $statusBadge['color'] }} border text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                            {{ $statusBadge['label'] }}
                        </span>
                        <h1 class="font-serif text-3xl font-bold text-stone-900">
                            Catering Proposal #{{ $cateringRequest->reference }}
                        </h1>
                        <p class="text-xs text-stone-500 mt-1">
                            Client: <strong class="text-stone-700">{{ $cateringRequest->customer_name }}</strong> • Event Date: <strong class="text-stone-700">{{ \Carbon\Carbon::parse($cateringRequest->event_date)->format('M d, Y') }}</strong>
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="window.print()" class="bg-stone-100 hover:bg-stone-200 text-stone-700 px-4 py-2 rounded-xl text-xs font-bold">
                            Print Proposal
                        </button>
                    </div>
                </div>

                <!-- Event Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 bg-stone-50 rounded-2xl p-6 border border-stone-200/80 mb-8 text-xs sm:text-sm">
                    <div>
                        <span class="text-stone-500 block uppercase font-bold text-[11px] mb-1">Expected Guests:</span>
                        <strong class="text-base text-stone-900 font-serif">{{ $cateringRequest->guest_count }} Attendees</strong>
                    </div>
                    <div>
                        <span class="text-stone-500 block uppercase font-bold text-[11px] mb-1">Venue Location:</span>
                        <strong class="text-stone-900 block truncate">{{ $cateringRequest->venue }}</strong>
                    </div>
                    <div>
                        <span class="text-stone-500 block uppercase font-bold text-[11px] mb-1">Selected Package:</span>
                        <strong class="text-emerald-800 font-bold block">{{ $cateringRequest->package?->name ?? 'Custom Bespoke Owambe Menu' }}</strong>
                    </div>
                </div>

                <!-- Dietary & Notes -->
                @if($cateringRequest->dietary_notes)
                    <div class="mb-8 p-4 rounded-xl bg-amber-50/70 border border-amber-200/60 text-xs text-stone-700">
                        <strong class="font-bold text-amber-950 block mb-1">Client Dietary Notes & Specifications:</strong>
                        <p>{{ $cateringRequest->dietary_notes }}</p>
                    </div>
                @endif

                <!-- Quotation Financials -->
                @if($cateringRequest->quoted_total_minor)
                    <div class="border-2 border-emerald-600/30 bg-emerald-50/40 rounded-3xl p-6 sm:p-8 mb-8">
                        <h3 class="font-serif text-xl font-bold text-stone-900 mb-4">
                            Official Catering Quotation
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center border-b border-stone-200 pb-6 mb-6">
                            <div>
                                <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block mb-1">Total Event Cost</span>
                                <span class="font-serif text-2xl sm:text-3xl font-extrabold text-[#0D4A2B] block">
                                    {{ \App\Support\Money::format($cateringRequest->quoted_total_minor, $cateringRequest->currency) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-bold tracking-wider text-amber-700 block mb-1">Deposit (To Lock Date)</span>
                                <span class="font-serif text-2xl sm:text-3xl font-extrabold text-amber-600 block">
                                    {{ \App\Support\Money::format($cateringRequest->deposit_minor ?? ($cateringRequest->quoted_total_minor * 0.5), $cateringRequest->currency) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block mb-1">Remaining Balance</span>
                                <span class="font-serif text-2xl sm:text-3xl font-extrabold text-stone-800 block">
                                    {{ \App\Support\Money::format($cateringRequest->quoted_total_minor - ($cateringRequest->deposit_minor ?? ($cateringRequest->quoted_total_minor * 0.5)), $cateringRequest->currency) }}
                                </span>
                            </div>
                        </div>

                        @if($cateringRequest->admin_notes)
                            <div class="text-xs text-stone-700 space-y-1 mb-6">
                                <strong class="text-stone-900 font-bold block">Chef & Coordinator Inclusions:</strong>
                                <p class="leading-relaxed">{{ $cateringRequest->admin_notes }}</p>
                            </div>
                        @endif

                        <!-- Action: Accept Quote & Pay Deposit -->
                        @if(in_array($cateringRequest->status, ['quote_sent', 'submitted', 'under_review']))
                            <form method="POST" action="{{ route('catering.accept', $cateringRequest->reference) }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="payment_type" value="deposit">

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                        Choose Payment Gateway for Deposit:
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
                                    <span>Accept Quotation & Secure Date with Deposit</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </form>
                        @elseif($cateringRequest->status === 'deposit_paid')
                            <!-- Action: Pay Balance -->
                            <div class="p-4 rounded-2xl bg-emerald-100 text-emerald-900 text-xs font-medium mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-700 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Deposit verified! Your date is secured. The balance is due 48 hours prior to event delivery.</span>
                            </div>

                            <form method="POST" action="{{ route('catering.accept', $cateringRequest->reference) }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="payment_type" value="balance">
                                <input type="hidden" name="payment_method" value="paystack">

                                <button 
                                    type="submit" 
                                    class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-3.5 px-6 rounded-xl font-bold text-sm shadow transition-all flex items-center justify-center gap-2"
                                >
                                    <span>Pay Remaining Event Balance ({{ \App\Support\Money::format($cateringRequest->quoted_total_minor - $cateringRequest->deposit_minor, $cateringRequest->currency) }})</span>
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="p-8 rounded-3xl bg-amber-50/70 border border-amber-200 text-center mb-8">
                        <div class="w-12 h-12 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center mx-auto mb-3 font-serif font-bold text-xl">
                            ⏳
                        </div>
                        <h3 class="font-serif text-lg font-bold text-stone-900 mb-1">Quotation In Preparation</h3>
                        <p class="text-stone-600 text-xs max-w-md mx-auto">
                            Our event coordinator is currently analyzing market produce pricing and staff logistics for {{ $cateringRequest->guest_count }} guests. You will receive an email quote shortly.
                        </p>
                    </div>
                @endif

                <!-- Direct Concierge Link -->
                <div class="pt-6 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                    <a 
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber ?? '2348000000000') }}?text={{ urlencode('Hello! I would like to discuss catering proposal #' . $cateringRequest->reference) }}" 
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 text-emerald-800 font-bold hover:underline"
                    >
                        <span>💬 Speak with Event Coordinator on WhatsApp</span>
                    </a>
                    <a href="{{ route('catering.index') }}" class="text-stone-500 hover:text-stone-900">
                        ← Back to Catering Overview
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

