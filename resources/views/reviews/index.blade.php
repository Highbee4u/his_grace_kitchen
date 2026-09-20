@extends('layouts.storefront')

@section('title', 'Customer Reviews & Dining Feedback | ' . ($siteName ?? 'Nigerian Kitchen'))
@section('meta_description', 'Read authentic customer reviews and food lover experiences for Nigerian Kitchen. Smoky party jollof, slow-simmered native soups, and event catering.')

@section('content')
<div class="bg-[#FCFBF7] py-12 sm:py-16" x-data="{
    showForm: false,
    rating: 5,
    hoverRating: 5
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <!-- Page Header & Rating Summary -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-stone-200/80 shadow-sm relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Left: Title & Average -->
                <div class="lg:col-span-7 space-y-4">
                    <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                        ★ Verified Customer Feedback
                    </span>
                    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-extrabold text-stone-900 leading-tight">
                        What Food Lovers Say About Us
                    </h1>
                    <p class="text-stone-600 text-sm sm:text-base leading-relaxed">
                        Read real stories from our dining guests, home delivery patrons, and international diaspora customers savoring our authentic firewood jollof, rich native soups, and festive feasts.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <button 
                            type="button" 
                            @click="showForm = !showForm" 
                            class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-6 py-3 rounded-full text-sm font-bold shadow-md transition-all flex items-center gap-2"
                        >
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                            <span x-text="showForm ? 'Close Review Form' : 'Write a Review'"></span>
                        </button>
                        <a 
                            href="{{ route('menu.index') }}" 
                            class="bg-stone-100 hover:bg-stone-200 text-stone-800 px-6 py-3 rounded-full text-sm font-bold transition-all"
                        >
                            Explore Menu
                        </a>
                    </div>
                </div>

                <!-- Right: Overall Score Card -->
                <div class="lg:col-span-5 bg-gradient-to-br from-stone-900 to-stone-950 text-white rounded-2xl p-6 sm:p-8 shadow-xl border border-stone-800">
                    <div class="flex items-center justify-between border-b border-stone-800 pb-4 mb-4">
                        <div>
                            <div class="text-4xl sm:text-5xl font-black text-amber-400 font-serif">
                                {{ number_format($averageRating, 1) }}
                            </div>
                            <div class="flex text-amber-400 text-base mt-1">
                                ★★★★★
                            </div>
                            <span class="text-xs text-stone-400 mt-1 block">Based on {{ $totalCount }} verified reviews</span>
                        </div>
                        <div class="text-right">
                            <span class="bg-amber-500/20 text-amber-300 text-[11px] font-bold px-2.5 py-1 rounded-full border border-amber-500/30 uppercase tracking-wider">
                                99% Satisfaction
                            </span>
                        </div>
                    </div>

                    <!-- Breakdown Bars -->
                    <div class="space-y-1.5 text-xs">
                        @for ($star = 5; $star >= 1; $star--)
                            @php
                                $cnt = $ratingCounts[$star] ?? 0;
                                $pct = $totalCount > 0 ? ($cnt / $totalCount) * 100 : ($star === 5 ? 100 : 0);
                            @endphp
                            <div class="flex items-center gap-2 text-stone-400">
                                <span class="w-12 text-right">{{ $star }} Star</span>
                                <div class="flex-1 bg-stone-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-amber-400 h-full rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="w-6 text-right text-[11px]">{{ $cnt }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Flash Message -->
        @if (session('review_success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-600 p-5 rounded-2xl shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-lg shrink-0">
                        ✓
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-900">Review Submitted Successfully!</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('review_success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Write a Review Form (Collapsible / Dynamic) -->
        <div 
            x-show="showForm" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-emerald-600/30 shadow-xl"
        >
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="border-b border-stone-100 pb-4">
                    <span class="text-xs font-bold text-[#0D4A2B] uppercase tracking-wider">Share Your Story</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Leave a Dining Review</h2>
                    <p class="text-stone-500 text-xs sm:text-sm mt-1">Your honest review helps other food lovers find the best firewood jollof and native soups.</p>
                </div>

                <form method="POST" action="{{ route('reviews.store') }}" class="space-y-6">
                    @csrf

                    <!-- Anti-spam Honeypot -->
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                    <!-- Interactive Star Picker -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                            Overall Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-1.5">
                            <template x-for="i in 5" :key="i">
                                <button 
                                    type="button" 
                                    @click="rating = i; hoverRating = i;"
                                    @mouseenter="hoverRating = i"
                                    @mouseleave="hoverRating = rating"
                                    class="text-3xl sm:text-4xl transition-transform hover:scale-110 focus:outline-hidden"
                                    :class="i <= hoverRating ? 'text-amber-400' : 'text-stone-200'"
                                >
                                    ★
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="rating">
                            <span class="ml-3 text-xs font-bold text-stone-600" x-text="rating + ' / 5 Stars'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Your Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="customer_name" 
                                id="customer_name" 
                                value="{{ old('customer_name', auth()->user()?->name) }}" 
                                required 
                                placeholder="e.g. Dr. Folake Adeyemi"
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                            >
                        </div>

                        <div>
                            <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Email Address (Private / Not Publicly Displayed)
                            </label>
                            <input 
                                type="email" 
                                name="customer_email" 
                                id="customer_email" 
                                value="{{ old('customer_email', auth()->user()?->email) }}" 
                                placeholder="e.g. folake@example.com"
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="customer_location" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Your Location / City
                            </label>
                            <input 
                                type="text" 
                                name="customer_location" 
                                id="customer_location" 
                                value="{{ old('customer_location') }}" 
                                placeholder="e.g. Lekki Phase 1, Lagos or South London, UK"
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                            >
                        </div>

                        <div>
                            <label for="menu_item_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Dish Savored (Optional)
                            </label>
                            <select 
                                name="menu_item_id" 
                                id="menu_item_id" 
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                            >
                                <option value="">Select a dish from our menu...</option>
                                @foreach ($dishes as $dish)
                                    <option value="{{ $dish->id }}" {{ old('menu_item_id') == $dish->id ? 'selected' : '' }}>
                                        {{ $dish->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="order_number" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Order Number (Optional - for Verified Buyer Badge)
                            </label>
                            <input 
                                type="text" 
                                name="order_number" 
                                id="order_number" 
                                value="{{ old('order_number') }}" 
                                placeholder="e.g. NK-20260920-ABCD"
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                            >
                        </div>

                        <div>
                            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                                Review Headline
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                value="{{ old('title') }}" 
                                placeholder="e.g. Real firewood smoke flavor in every bite!"
                                class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-2.5"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="comment" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">
                            Your Review <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="comment" 
                            id="comment" 
                            rows="4" 
                            required 
                            placeholder="Tell us about the flavors, warmth, packaging, and your overall dining experience..."
                            class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm p-3"
                        >{{ old('comment') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button 
                            type="button" 
                            @click="showForm = false" 
                            class="px-5 py-2.5 rounded-full text-xs font-bold text-stone-600 hover:text-stone-900 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="bg-[#0D4A2B] hover:bg-[#09351e] text-white px-8 py-3 rounded-full text-sm font-bold shadow-md transition-all"
                        >
                            Submit My Review
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reviews Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-stone-200 pb-4">
                <h3 class="font-serif text-2xl font-bold text-stone-900">
                    Latest Community Reviews ({{ $totalCount }})
                </h3>
            </div>

            @if ($reviews->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-stone-200 shadow-sm max-w-xl mx-auto">
                    <div class="text-4xl mb-3">🍲</div>
                    <h4 class="font-serif text-xl font-bold text-stone-800">Be the First to Leave a Review!</h4>
                    <p class="text-stone-500 text-xs mt-1 max-w-sm mx-auto">Enjoyed our firewood jollof or hot catfish pepper soup? Share your dining thoughts with our kitchen team.</p>
                    <button 
                        type="button" 
                        @click="showForm = true" 
                        class="mt-4 bg-[#0D4A2B] text-white px-6 py-2.5 rounded-full text-xs font-bold shadow hover:bg-[#09351e]"
                    >
                        Write First Review
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($reviews as $review)
                        <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between space-y-4 hover:shadow-md transition-shadow">
                            <div class="space-y-3">
                                <!-- Star Rating & Badge -->
                                <div class="flex items-center justify-between">
                                    <div class="text-amber-500 font-bold text-base tracking-wider">
                                        {{ $review->stars }}
                                    </div>
                                    @if ($review->is_verified_buyer)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">
                                            <span>✓</span> Verified Buyer
                                        </span>
                                    @endif
                                </div>

                                @if ($review->title)
                                    <h4 class="font-serif font-bold text-stone-900 text-base leading-snug">
                                        "{{ $review->title }}"
                                    </h4>
                                @endif

                                <p class="text-stone-700 text-xs sm:text-sm leading-relaxed italic">
                                    "{{ $review->comment }}"
                                </p>

                                @if ($review->admin_response)
                                    <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200 text-xs text-stone-800 space-y-1">
                                        <div class="flex items-center gap-1.5 text-[#0D4A2B] font-bold">
                                            <span>👨‍🍳 Chef's Kitchen Response:</span>
                                        </div>
                                        <p class="text-stone-600 leading-relaxed">{{ $review->admin_response }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                                <div>
                                    <span class="font-bold text-stone-900 block">{{ $review->customer_name }}</span>
                                    <span>
                                        {{ $review->customer_location ? $review->customer_location . ' • ' : '' }}
                                        <span class="text-emerald-800 font-medium">{{ $review->dish_title }}</span>
                                    </span>
                                </div>
                                <span class="text-[11px] text-stone-400">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

