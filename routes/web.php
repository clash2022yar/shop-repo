<?php

use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\DashboardController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\TicketController;
use App\Http\Controllers\Account\WishlistController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Digino — public storefront routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// ------------------------------------------------------------------ catalog
Route::get('/products', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/brands', [ShopController::class, 'brands'])->name('brands.index');
Route::get('/brand/{brand}', [ShopController::class, 'brand'])->name('brands.show');
Route::get('/special-offers', [ShopController::class, 'special'])->name('shop.special');
Route::get('/compare', [CompareController::class, 'index'])->name('compare');

// --------------------------------------------------------------------- cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// ----------------------------------------------------------------- checkout
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/{order}', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/result/{order}', [CheckoutController::class, 'result'])->name('checkout.result');
});

// --------------------------------------------------------------------- blog
Route::get('/magazine', [BlogController::class, 'index'])->name('blog.index');
Route::get('/magazine/{post}', [BlogController::class, 'show'])->name('blog.show');

// ---------------------------------------------------------- static content
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/p/{page}', [PageController::class, 'show'])->name('pages.show');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/forgot-password', [LoginController::class, 'forgot'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendReset'])->name('password.email');
    Route::get('/reset-password/{token}', [LoginController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'updatePassword'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Customer dashboard  (/panel)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->prefix('panel')->name('account.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [AccountOrderController::class, 'invoice'])->name('orders.invoice');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/recently-viewed', [DashboardController::class, 'recentlyViewed'])->name('recently-viewed');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::get('/security', [ProfileController::class, 'security'])->name('security');
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
    Route::get('/payments', [ProfileController::class, 'payments'])->name('payments');

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    Route::get('/reviews', [ProfileController::class, 'reviews'])->name('reviews');
});
