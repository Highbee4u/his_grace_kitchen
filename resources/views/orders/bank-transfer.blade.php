@extends('layouts.storefront')

@section('title', "Bank Transfer Instructions - Order #{$order->order_number} | His Grace Kitchen LTD")

@section('content')
    <div class="bg-[#FCFBF7] py-12 sm:py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-stone-200 shadow-md">
                <div class="text-center mb-8">
                    <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                        🏦
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 block mb-1">Awaiting Bank Transfer</span>
                    <h1 class="font-serif text-3xl font-bold text-stone-900">
                        Order #{{ $order->order_number }}
                    </h1>
                    <p class="text-stone-500 text-xs sm:text-sm mt-1">
                        Please complete your bank transfer using the account details below.
                    </p>
                </div>

                <!-- Amount to Pay Callout -->
                <div class="bg-emerald-50 border-2 border-emerald-300/80 rounded-2xl p-5 text-center mb-8">
                    <span class="text-xs uppercase font-bold tracking-wider text-emerald-800 block mb-1">Exact Amount to Transfer:</span>
                    <span class="font-serif text-3xl font-extrabold text-[#0D4A2B] block">
                        {{ \App\Support\Money::format($order->total_minor, $order->currency) }}
                    </span>
                    <div class="mt-2 inline-flex items-center gap-1.5 bg-white px-3 py-1 rounded-full border border-emerald-200 text-xs font-bold text-stone-700">
                        <span>Payment Narration:</span>
                        <code class="text-emerald-800 font-mono">{{ $order->order_number }}</code>
                    </div>
                </div>

                <!-- Bank Accounts Grid -->
                <div class="space-y-4 mb-8">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-stone-700">Official Kitchen Bank Accounts</h3>

                    <!-- GTBank -->
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-sm text-stone-900 block">Guaranty Trust Bank (GTBank)</span>
                            <span class="text-xs text-stone-500">His Grace Kitchen LTD Hospitality Ltd</span>
                        </div>
                        <div class="text-right">
                            <span class="font-mono text-base font-extrabold text-stone-900 block select-all">0123456789</span>
                            <span class="text-[10px] text-emerald-700 font-bold">Naira Account</span>
                        </div>
                    </div>

                    <!-- Zenith Bank -->
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-sm text-stone-900 block">Zenith Bank</span>
                            <span class="text-xs text-stone-500">His Grace Kitchen LTD Hospitality Ltd</span>
                        </div>
                        <div class="text-right">
                            <span class="font-mono text-base font-extrabold text-stone-900 block select-all">1012345678</span>
                            <span class="text-[10px] text-emerald-700 font-bold">Naira Account</span>
                        </div>
                    </div>
                </div>

                <!-- Proof Submission via WhatsApp -->
                <div class="space-y-3 pt-2 border-t border-stone-100 text-center">
                    <p class="text-xs text-stone-600">
                        Once transferred, send a screenshot of your bank transfer receipt via WhatsApp for instant automated release of your kitchen ticket:
                    </p>
                    <a 
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber ?? '2348000000000') }}?text={{ urlencode('Hello His Grace Kitchen LTD! Here is my payment receipt for order #' . $order->order_number . ' (' . \App\Support\Money::format($order->total_minor, $order->currency) . ')') }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-xl text-sm shadow transition-colors"
                    >
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Upload Receipt on WhatsApp
                    </a>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('orders.show', $order->order_number) }}" class="text-xs font-bold text-stone-500 hover:text-stone-900 underline">
                        View Order Status Page →
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

