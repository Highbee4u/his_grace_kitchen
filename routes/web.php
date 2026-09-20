<?php

use App\Http\Controllers\CateringController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpecialRequestController;
use Illuminate\Support\Facades\Route;

// Public Storefront Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{slug}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/combos', [ComboController::class, 'index'])->name('combos.index');
Route::get('/catering', [CateringController::class, 'index'])->name('catering.index');
Route::post('/catering', [CateringController::class, 'store'])->name('catering.store')->middleware('throttle:15,1');
Route::get('/catering/{reference}', [CateringController::class, 'show'])->name('catering.show');
Route::post('/catering/{reference}/accept', [CateringController::class, 'accept'])->name('catering.accept');

Route::get('/special-request', [SpecialRequestController::class, 'create'])->name('special-requests.create');
Route::post('/special-request', [SpecialRequestController::class, 'store'])->name('special-requests.store')->middleware('throttle:15,1');
Route::get('/special-request/{reference}', [SpecialRequestController::class, 'show'])->name('special-requests.show');
Route::post('/special-request/{reference}/accept', [SpecialRequestController::class, 'accept'])->name('special-requests.accept');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// XML Sitemap
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (! file_exists($path)) {
        \Illuminate\Support\Facades\Artisan::call('sitemap:generate');
    }

    return response(file_get_contents($path), 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

// Checkout & Orders
Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:20,1');

Route::get('/orders/{order_number}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order_number}/invoice', [\App\Http\Controllers\InvoiceController::class, 'orderInvoice'])->name('orders.invoice');
Route::get('/orders/{order_number}/bank-transfer', [\App\Http\Controllers\OrderController::class, 'bankTransfer'])->name('orders.bank-transfer');
Route::match(['get', 'post'], '/track-order', [\App\Http\Controllers\OrderController::class, 'track'])->name('orders.track')->middleware('throttle:60,1');

// Invoices (PDF Download & Stream)
Route::get('/invoices/{number}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('invoices.download');
Route::get('/invoices/{number}/stream', [\App\Http\Controllers\InvoiceController::class, 'stream'])->name('invoices.stream');

// Payment Webhooks
Route::post('/webhooks/paystack', [\App\Http\Controllers\WebhookController::class, 'handlePaystack'])->name('webhooks.paystack');
Route::post('/webhooks/stripe', [\App\Http\Controllers\WebhookController::class, 'handleStripe'])->name('webhooks.stripe');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
