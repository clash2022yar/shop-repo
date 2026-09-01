<?php

use App\Http\Controllers\Ajax\AccountAjaxController;
use App\Http\Controllers\Ajax\CartAjaxController;
use App\Http\Controllers\Ajax\CatalogAjaxController;
use App\Http\Controllers\Ajax\CheckoutAjaxController;
use App\Http\Controllers\Ajax\NewsletterAjaxController;
use App\Http\Controllers\Ajax\ReviewAjaxController;
use App\Http\Controllers\Ajax\WishlistAjaxController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AJAX endpoints  (prefix: /ajax, name: ajax.*)
|--------------------------------------------------------------------------
|
| These power the no-reload behaviour of the storefront. Everything returns
| the same JSON envelope: { ok, message, ...payload }, so the tiny fetch
| wrapper in public/js/app.js can handle success and failure uniformly.
|
*/

// -------------------------------------------------------------------- cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartAjaxController::class, 'show'])->name('show');
    Route::get('/mini', [CartAjaxController::class, 'mini'])->name('mini');
    Route::post('/add', [CartAjaxController::class, 'add'])->name('add');
    Route::patch('/items/{item}', [CartAjaxController::class, 'update'])->name('update');
    Route::delete('/items/{item}', [CartAjaxController::class, 'destroy'])->name('destroy');
    Route::post('/items/{item}/select', [CartAjaxController::class, 'select'])->name('select');
    Route::post('/select-all', [CartAjaxController::class, 'selectAll'])->name('select-all');
    Route::delete('/selected', [CartAjaxController::class, 'destroySelected'])->name('destroy-selected');
    Route::delete('/', [CartAjaxController::class, 'clear'])->name('clear');
    Route::post('/coupon', [CartAjaxController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon', [CartAjaxController::class, 'removeCoupon'])->name('coupon.remove');
});

// ----------------------------------------------------------------- catalog
Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/suggest', [CatalogAjaxController::class, 'suggest'])->name('suggest');
    Route::get('/products', [CatalogAjaxController::class, 'products'])->name('products');
    Route::get('/quick-view/{product}', [CatalogAjaxController::class, 'quickView'])->name('quick-view');
    Route::get('/variant-price/{product}', [CatalogAjaxController::class, 'variantPrice'])->name('variant-price');
    Route::get('/mega-menu/{category}', [CatalogAjaxController::class, 'megaMenu'])->name('mega-menu');
    Route::get('/product/{product}/tab/{tab}', [CatalogAjaxController::class, 'tab'])->name('tab');
});

// ---------------------------------------------------------------- wishlist
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/{product}', [WishlistAjaxController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistAjaxController::class, 'index'])->name('wishlist.index');
});

// ----------------------------------------------------------------- reviews
Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/product/{product}', [ReviewAjaxController::class, 'index'])->name('index');
    Route::post('/product/{product}', [ReviewAjaxController::class, 'store'])->middleware('auth')->name('store');
    Route::post('/{review}/vote', [ReviewAjaxController::class, 'vote'])->name('vote');
    Route::post('/questions/{product}', [ReviewAjaxController::class, 'ask'])->middleware('auth')->name('ask');
    Route::post('/questions/{question}/answer', [ReviewAjaxController::class, 'answer'])->middleware('auth')->name('answer');
});

// ---------------------------------------------------------------- checkout
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::post('/shipping', [CheckoutAjaxController::class, 'shipping'])->name('shipping');
    Route::post('/address', [CheckoutAjaxController::class, 'storeAddress'])->name('address.store');
    Route::post('/select-address', [CheckoutAjaxController::class, 'selectAddress'])->name('address.select');
    Route::get('/summary', [CheckoutAjaxController::class, 'summary'])->name('summary');
});

// ----------------------------------------------------------------- account
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::put('/profile', [AccountAjaxController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AccountAjaxController::class, 'updatePassword'])->name('password.update');
    Route::post('/addresses', [AccountAjaxController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [AccountAjaxController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountAjaxController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [AccountAjaxController::class, 'makeDefaultAddress'])->name('addresses.default');
    Route::post('/orders/{order}/cancel', [AccountAjaxController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/tickets', [AccountAjaxController::class, 'storeTicket'])->name('tickets.store');
    Route::post('/tickets/{ticket}/reply', [AccountAjaxController::class, 'replyTicket'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/close', [AccountAjaxController::class, 'closeTicket'])->name('tickets.close');
});

// -------------------------------------------------------------- newsletter
Route::post('/newsletter', [NewsletterAjaxController::class, 'store'])->name('newsletter');
Route::post('/contact', [NewsletterAjaxController::class, 'contact'])->name('contact');
