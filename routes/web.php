<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\isCustomer;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('about-us', [HomeController::class, 'about'])->name('about');
Route::get('contact-us', [HomeController::class, 'contact'])->name('contact');
Route::post('contact', [HomeController::class, 'contactSend'])->name('contact.send');

Route::get('blogs', [HomeController::class, 'blog'])->name('blogs.index');
Route::get('blogs/{slug}', [HomeController::class, 'blogShow'])->name('blogs.show');

Route::get('/product', [HomeController::class, 'products'])->name('home.products');
Route::get('/product/{id}', [HomeController::class, 'product_detail'])->name('home.products.detail');

// Cart Routes
Route::middleware(['auth', isCustomer::class])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');

    // Checkout process
    Route::get('checkout', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');

    // Payment pages
    Route::get('checkout/success', [OrderController::class, 'paymentSuccess'])->name('checkout.success');
    Route::get('checkout/pending', [OrderController::class, 'paymentPending'])->name('checkout.pending');
    Route::get('checkout/failed', [OrderController::class, 'paymentFailed'])->name('checkout.failed');

    // Order management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'myOrders'])->name('index');
        Route::get('{id}', [OrderController::class, 'show'])->name('show');
        Route::post('{id}/upload-payment', [OrderController::class, 'uploadPaymentProof'])->name('upload-payment');
        Route::post('{id}/confirm', [OrderController::class, 'confirmOrder'])->name('confirm');
        Route::post('{id}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel');
        Route::get('{id}/retry-payment', [OrderController::class, 'retryPayment'])->name('retry-payment');
        Route::get('{id}/payment-status', [OrderController::class, 'getPaymentStatus'])->name('payment-status');

        Route::get('/{id}/invoice', [OrderController::class, 'invoice'])->name('invoice');
    });

    // Midtrans Notification Handler - No Auth Middleware
    Route::post('payments/notification', [OrderController::class, 'handlePaymentNotification'])
        ->name('payment.notification');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'getProvinces'])->name('rajaongkir.provinces');
    Route::get('/rajaongkir/cities', [RajaOngkirController::class, 'getCities'])->name('rajaongkir.cities');
    Route::post('/rajaongkir/cost', [RajaOngkirController::class, 'getShippingCost'])->name('rajaongkir.cost');
});

Route::get('orders/{id}/invoice', [OrderController::class, 'invoice'])
    ->middleware('auth')
    ->name('orders.invoice');

Route::get('login', function () {
    return redirect()->route('filament.app.auth.login');
})->name('login');

Route::post('/logout', [HomeController::class, 'logout'])
    ->name('logout');
