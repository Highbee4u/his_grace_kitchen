@extends('layouts.storefront')

@section('title', 'Special Custom Dish Request | His Grace Kitchen LTD')
@section('meta_description', 'Craving a specific Nigerian regional delicacy not on the daily menu? Request custom pots of Afang, Fisherman Soup, Bitterleaf, Banga, or Tuwo cooked fresh to order.')

@section('content')
    <!-- Hero Banner -->
    <section class="relative bg-gradient-to-b from-stone-950 via-[#0D4A2B] to-stone-900 text-white py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#F59E0B_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 border border-amber-500/30 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg>
                Off-Menu Traditional Delicacies
            </span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mb-6">
                Special & Custom <span class="text-amber-400">Dish Requests</span>
            </h1>
            <p class="text-stone-300 text-base sm:text-lg max-w-2xl mx-auto font-light leading-relaxed">
                Craving an authentic regional classic from back home? Whether it’s Calabar Afang, Rivers Fisherman Soup, Bitterleaf with Cocoyam, or Delta Banga & Starch, our master chefs can source native ingredients and cook it exclusively for you.
            </p>
        </div>
    </section>

    <!-- Content & Form Section -->
    <section class="py-16 sm:py-20 bg-[#FCFBF7]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                <!-- Left Sidebar: How It Works & Popular Inspiration -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Process Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200/80 shadow-sm">
                        <h3 class="font-serif text-xl font-bold text-stone-900 mb-4 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-amber-500 text-stone-950 flex items-center justify-center text-xs font-black">i</span>
                            How It Works
                        </h3>
                        <ol class="space-y-4 text-xs sm:text-sm text-stone-600">
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-emerald-800 shrink-0">1.</span>
                                <div>
                                    <strong class="text-stone-900 block font-semibold">Describe Your Dish:</strong>
                                    Share the traditional delicacy name, portion size, protein mix, and spicy heat level.
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-emerald-800 shrink-0">2.</span>
                                <div>
                                    <strong class="text-stone-900 block font-semibold">Chef Market Sourcing:</strong>
                                    Our head chef assesses fresh native market produce (fresh scent leaf, periwinkles, yellow pepper, dry fish).
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="font-bold text-emerald-800 shrink-0">3.</span>
                                <div>
                                    <strong class="text-stone-900 block font-semibold">Custom Quote & Cooking:</strong>
                                    We send a quotation link. Upon confirmation, your dish is simmered fresh and delivered hot or blast-chilled for courier.
                                </div>
                            </li>
                        </ol>
                    </div>

                    <!-- Regional Favorites Inspiration -->
                    <div class="bg-gradient-to-br from-[#0D4A2B] to-[#09351e] text-white rounded-3xl p-6 sm:p-8 shadow-sm">
                        <h4 class="font-serif text-lg font-bold text-amber-300 mb-3">
                            Popular Custom Requests
                        </h4>
                        <ul class="space-y-2.5 text-xs text-stone-200">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span>Calabar Afang Soup with Waterleaf & Periwinkle</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span>Rivers State Native Fisherman Soup</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span>Ofe Owerri & Bitterleaf with Cocoyam</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span>Delta Banga Soup with Yellow Starch</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span>Abacha & Ugba (African Salad with Fried Fish)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span>Northern Tuwo Shinkafa & Miyan Kuka</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Column: Request Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-stone-200/80 shadow-md">
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mb-2">
                            Send Your Custom Dish Request
                        </h2>
                        <p class="text-stone-500 text-xs sm:text-sm mb-8">
                            Fill out the details below. We'll consult with our kitchen team and send you price & delivery estimates.
                        </p>

                        <form method="POST" action="{{ route('special-requests.store') }}" class="space-y-6">
                            @csrf
                            <input type="text" name="website_hp" style="display:none !important; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">

                            @if ($errors->any())
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                                    <div class="flex">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Please correct the highlighted fields:</h3>
                                            <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Customer Name -->
                                <div>
                                    <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                        Your Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="customer_name" 
                                        id="customer_name" 
                                        value="{{ old('customer_name', auth()->user()?->name) }}"
                                        required 
                                        placeholder="e.g. Ngozi Eze"
                                        class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                                    >
                                </div>

                                <!-- Customer Email -->
                                <div>
                                    <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        name="customer_email" 
                                        id="customer_email" 
                                        value="{{ old('customer_email', auth()->user()?->email) }}"
                                        required 
                                        placeholder="ngozi@example.com"
                                        class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                                    >
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="customer_phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                    Phone Number (WhatsApp Active) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    name="customer_phone" 
                                    id="customer_phone" 
                                    value="{{ old('customer_phone') }}"
                                    required 
                                    placeholder="07988 575 682 or 07508 282 876"
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                                >
                            </div>

                            <!-- Dish Description -->
                            <div>
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                    Dish Name & Detailed Preparation Notes <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    name="description" 
                                    id="description" 
                                    rows="5" 
                                    required
                                    placeholder="e.g. 5-Litre Pot of Authentic Calabar Afang Soup prepared with waterleaf, shelled periwinkles, smoked catfish, dried prawns, and goat meat. Medium pepper, no MSG."
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm p-3"
                                >{{ old('description') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <!-- Quantity / Portions -->
                                <div>
                                    <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                        Quantity / Portions <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        id="quantity" 
                                        min="1" 
                                        value="{{ old('quantity', 1) }}"
                                        required 
                                        class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                                    >
                                </div>

                                <!-- Needed By Date -->
                                <div>
                                    <label for="needed_by" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                        Needed By Date
                                    </label>
                                    <input 
                                        type="date" 
                                        name="needed_by" 
                                        id="needed_by" 
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        value="{{ old('needed_by') }}"
                                        class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                                    >
                                </div>

                                <!-- Approximate Budget -->
                                <div>
                                    <label for="budget" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                        Target Budget (₦)
                                    </label>
                                    <input 
                                        type="number" 
                                        name="budget" 
                                        id="budget" 
                                        min="0"
                                        step="500"
                                        placeholder="e.g. 35000"
                                        value="{{ old('budget') }}"
                                        class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                                    >
                                </div>
                            </div>

                            <!-- Reference Image URL -->
                            <div>
                                <label for="reference_image_url" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">
                                    Reference Dish Image URL (Optional)
                                </label>
                                <input 
                                    type="url" 
                                    name="reference_image_url" 
                                    id="reference_image_url" 
                                    value="{{ old('reference_image_url') }}"
                                    placeholder="https://example.com/my-favorite-dish.jpg"
                                    class="w-full rounded-xl border-stone-300 focus:border-[#0D4A2B] focus:ring-[#0D4A2B] text-sm py-3"
                                >
                                <p class="text-stone-400 text-xs mt-1">If you have a photo of how you like it served, paste the link here.</p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button 
                                    type="submit" 
                                    class="w-full bg-[#0D4A2B] hover:bg-[#09351e] text-amber-300 hover:text-white py-4 px-6 rounded-2xl font-bold text-base shadow-lg transition-all duration-150 transform active:scale-98 flex items-center justify-center gap-2"
                                >
                                    <span>Send Special Request to Chef</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

