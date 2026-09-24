@extends('layouts.storefront')

@section('title', "Order #{$order->order_number} | His Grace Kitchen LTD")
@section('meta_description', "Live order tracking and receipt for His Grace Kitchen LTD Order #{$order->order_number}.")

@section('content')
    <div class="bg-[#FCFBF7] py-12 sm:py-16" x-init="$store.cart.clear()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Order Success Banner -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200/80 shadow-md">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-stone-100 pb-6 mb-6">
                    <div>
                        <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-ping"></span>
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                        <h1 class="font-serif text-3xl font-bold text-stone-900">
                            Order #{{ $order->order_number }}
                        </h1>
                        <p class="text-xs text-stone-500 mt-1">
                            Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }} • Fulfilment: <strong class="text-stone-700 capitalize">{{ $order->fulfilment_type }}</strong>
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <a 
                            href="{{ route('orders.invoice', $order->order_number) }}" 
                            class="inline-flex items-center gap-1.5 bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white px-4 py-2 rounded-xl text-xs font-bold shadow-xs transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Invoice (PDF)
                        </a>
                        <button 
                            type="button" 
                            onclick="window.print()" 
                            class="inline-flex items-center gap-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 px-4 py-2 rounded-xl text-xs font-bold transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>

                <!-- Live Fulfillment Timeline -->
                @php
                    $stages = [
                        'pending_payment' => ['label' => 'Order Received', 'desc' => 'Awaiting payment verification'],
                        'paid' => ['label' => 'Payment Verified', 'desc' => 'Queued for head chef'],
                        'confirmed' => ['label' => 'Order Confirmed', 'desc' => 'Ingredients prepped'],
                        'preparing' => ['label' => 'Simmering in Kitchen', 'desc' => 'Fresh firewood cooking'],
                        'out_for_delivery' => ['label' => $order->fulfilment_type === 'pickup' ? 'Ready for Collection' : 'Out for Delivery', 'desc' => $order->fulfilment_type === 'pickup' ? 'Waiting at the London kitchen' : 'Rider dispatched with thermal box'],
                        'delivered' => ['label' => 'Delivered', 'desc' => 'Enjoy your meal!'],
                    ];

                    $stageOrder = ['pending_payment', 'paid', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
                    $currentIndex = array_search($order->status, $stageOrder);
                    if ($currentIndex === false) $currentIndex = 1;
                @endphp

                <div class="mb-8 bg-stone-50 rounded-2xl p-6 border border-stone-100">
                    <h3 class="font-serif text-sm font-bold uppercase tracking-wider text-stone-800 mb-6 text-center sm:text-left">
                        Live Kitchen Status
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['pending_payment' => '1. Received', 'paid' => '2. Paid & Prepped', 'preparing' => '3. Cooking Now', 'delivered' => '4. Delivered'] as $stageKey => $stageTitle)
                            @php
                                $isCompleted = false;
                                $isActive = false;
                                
                                if ($order->status === 'delivered') {
                                    $isCompleted = true;
                                } elseif ($stageKey === 'pending_payment') {
                                    $isCompleted = true;
                                } elseif ($stageKey === 'paid' && in_array($order->status, ['paid', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'])) {
                                    $isCompleted = true;
                                } elseif ($stageKey === 'preparing' && in_array($order->status, ['preparing', 'out_for_delivery', 'delivered'])) {
                                    $isCompleted = true;
                                }

                                if ($order->status === $stageKey) {
                                    $isActive = true;
                                }
                            @endphp

                            <div class="text-center p-3 rounded-xl {{ $isActive ? 'bg-amber-100/70 border border-amber-300' : ($isCompleted ? 'bg-emerald-50 border border-emerald-200' : 'bg-white border border-stone-200 opacity-60') }}">
                                <div class="w-8 h-8 rounded-full {{ $isCompleted ? 'bg-emerald-600 text-white' : 'bg-stone-200 text-stone-600' }} flex items-center justify-center mx-auto mb-2 text-xs font-bold">
                                    @if($isCompleted)
                                        ✓
                                    @else
                                        •
                                    @endif
                                </div>
                                <span class="text-xs font-bold text-stone-900 block">{{ $stageTitle }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Receipt Items Table -->
                <div class="overflow-x-auto mb-8">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-[11px] uppercase tracking-wider text-stone-500 font-bold">
                                <th class="py-3">Dish / Item</th>
                                <th class="py-3 text-center">Qty</th>
                                <th class="py-3 text-right">Unit Price</th>
                                <th class="py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3.5 pr-4">
                                        <span class="font-bold text-stone-900 block">{{ $item->name }}</span>
                                        @if(!empty($item->options['variant']))
                                            <span class="text-xs text-amber-700 block">{{ $item->options['variant'] }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 text-center font-medium text-stone-700">{{ $item->quantity }}</td>
                                    <td class="py-3.5 text-right text-stone-600">
                                        {{ \App\Support\Money::format($item->unit_price_minor, $order->currency) }}
                                    </td>
                                    <td class="py-3.5 text-right font-bold text-stone-900">
                                        {{ \App\Support\Money::format($item->total_minor, $order->currency) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-stone-200 font-medium text-stone-700 text-sm">
                            <tr>
                                <td colspan="3" class="pt-4 text-right">Subtotal:</td>
                                <td class="pt-4 text-right font-bold text-stone-900">
                                    {{ \App\Support\Money::format($order->subtotal_minor, $order->currency) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="py-1 text-right">Delivery Fee:</td>
                                <td class="py-1 text-right font-bold text-stone-900">
                                    {{ \App\Support\Money::format($order->delivery_fee_minor, $order->currency) }}
                                </td>
                            </tr>
                            <tr class="text-base border-t border-stone-200">
                                <td colspan="3" class="pt-3 text-right font-extrabold text-stone-900">Total:</td>
                                <td class="pt-3 text-right font-serif font-extrabold text-xl text-[#0D4A2B]">
                                    {{ \App\Support\Money::format($order->total_minor, $order->currency) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Customer & Delivery Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-stone-200 text-xs">
                    <!-- Delivery Details -->
                    <div class="space-y-2">
                        <h4 class="font-bold uppercase tracking-wider text-stone-800">Fulfillment Details</h4>
                        @if($order->fulfilment_type === 'delivery')
                            <p class="text-stone-600 leading-relaxed">
                                <strong class="text-stone-800 block">{{ $order->delivery_address['street'] ?? 'Street Address' }}</strong>
                                {{ $order->delivery_address['city'] ?? '' }}, {{ $order->delivery_address['state'] ?? '' }}<br>
                                Zone: <span class="text-emerald-800 font-bold">{{ $order->deliveryZone?->name ?? 'Standard Zone' }}</span>
                            </p>
                            @if(!empty($order->delivery_address['instructions']))
                                <p class="text-stone-500 italic mt-1">
                                    Note: "{{ $order->delivery_address['instructions'] }}"
                                </p>
                            @endif
                        @else
                            <p class="text-stone-600 leading-relaxed">
                                <strong class="text-stone-800 block">Kitchen Pickup Counter:</strong>
                                Unit 15 Kencot Close, Business Park Kencot Way, DA18 4AB, London, UK.<br>
                                Please present Order #{{ $order->order_number }} to kitchen staff.
                            </p>
                        @endif
                    </div>

                    <!-- Customer & Contact -->
                    <div class="space-y-2">
                        <h4 class="font-bold uppercase tracking-wider text-stone-800">Customer Contact</h4>
                        <p class="text-stone-600">
                            <strong>{{ $order->customer_name }}</strong><br>
                            Email: {{ $order->customer_email }}<br>
                            Phone: {{ $order->customer_phone }}
                        </p>
                        @if($order->notes)
                            <p class="text-stone-500 mt-2">
                                <strong class="text-stone-700">Kitchen Instructions:</strong> {{ $order->notes }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- WhatsApp Updates CTA -->
                <div class="mt-8 pt-6 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a 
                        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber ?? '447988575682') }}?text={{ urlencode('Hello His Grace Kitchen! I would like an update on my order #' . $order->order_number) }}"
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-5 rounded-xl text-xs shadow transition-colors"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Get WhatsApp Rider Updates
                    </a>

                    <a href="{{ route('menu.index') }}" class="text-xs font-bold text-[#0D4A2B] hover:underline">
                        ← Return to Menu
                    </a>
                </div>
            </div>

            <!-- Leave a Review for this Order -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-md" x-data="{ orderReviewOpen: false, rating: 5, hoverRating: 5 }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">🌟 We Value Your Opinion</span>
                        <h3 class="font-serif text-xl sm:text-2xl font-bold text-stone-900">How was your His Grace Kitchen LTD experience?</h3>
                        <p class="text-stone-500 text-xs">Rate your dishes, rider delivery, or food quality to earn a Verified Buyer badge on your review.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="orderReviewOpen = !orderReviewOpen" 
                        class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-5 py-2.5 rounded-full text-xs font-bold shadow transition-all shrink-0"
                    >
                        <span x-text="orderReviewOpen ? 'Close Review Form' : '★ Rate Order & Leave Review'"></span>
                    </button>
                </div>

                @if (session('review_success'))
                    <div class="mt-4 bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-xl text-xs text-emerald-800">
                        {{ session('review_success') }}
                    </div>
                @endif

                <div x-show="orderReviewOpen" x-cloak class="mt-6 pt-6 border-t border-stone-100">
                    <form method="POST" action="{{ route('reviews.store') }}" class="space-y-4 max-w-2xl">
                        @csrf
                        <input type="hidden" name="order_number" value="{{ $order->order_number }}">
                        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Rating <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-1">
                                <template x-for="i in 5" :key="i">
                                    <button 
                                        type="button" 
                                        @click="rating = i; hoverRating = i;"
                                        @mouseenter="hoverRating = i"
                                        @mouseleave="hoverRating = rating"
                                        class="text-2xl transition-transform hover:scale-110 focus:outline-hidden"
                                        :class="i <= hoverRating ? 'text-amber-400' : 'text-stone-200'"
                                    >
                                        ★
                                    </button>
                                </template>
                                <input type="hidden" name="rating" :value="rating">
                                <span class="ml-2 text-xs font-bold text-stone-600" x-text="rating + ' / 5 Stars'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Customer Name <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" value="{{ $order->customer_name }}" required class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Location</label>
                                <input type="text" name="customer_location" value="{{ $order->delivery_address['city'] ?? '' }}" placeholder="e.g. Bexley or South London" class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Headline (Optional)</label>
                            <input type="text" name="title" placeholder="e.g. Hot and delicious! Arrived right on time." class="w-full rounded-xl border-stone-300 text-xs py-2 px-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Your Comments & Dining Feedback <span class="text-red-500">*</span></label>
                            <textarea name="comment" rows="3" required placeholder="Tell our kitchen and dispatch team about the flavor, hotness, packaging..." class="w-full rounded-xl border-stone-300 text-xs p-3 focus:border-[#0D4A2B] focus:ring-[#0D4A2B]"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="orderReviewOpen = false" class="px-4 py-2 rounded-full text-xs font-bold text-stone-600 hover:text-stone-900">Cancel</button>
                            <button type="submit" class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-6 py-2.5 rounded-full text-xs font-bold shadow transition-all">Submit Verified Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

