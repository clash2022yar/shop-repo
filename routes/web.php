<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/shop', [StoreController::class, 'products'])->name('shop');
Route::get('/products', [StoreController::class, 'products'])->name('products');
Route::get('/search', [StoreController::class, 'products'])->name('search');
Route::get('/search/suggestions', [StoreController::class, 'suggestions'])->name('suggestions');
Route::get('/category/{category:slug}', [StoreController::class, 'category'])->name('category');
Route::get('/product/{product:slug}', [StoreController::class, 'product'])->name('product');
Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
Route::post('/cart/{product}', [StoreController::class, 'addCart'])->name('cart.add');
Route::patch('/cart/{product}', [StoreController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/{product}', [StoreController::class, 'removeCart'])->name('cart.remove');
Route::post('/cart/coupon/apply', [StoreController::class, 'applyCoupon'])->name('cart.coupon');
Route::get('/about', [StoreController::class, 'about'])->name('about');
Route::post('/newsletter', [StoreController::class, 'newsletter'])->name('newsletter');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:4,1')->name('register');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [StoreController::class, 'placeOrder'])->middleware('throttle:5,1')->name('checkout.place');
    Route::post('/product/{product}/review', [StoreController::class, 'review'])->name('review.store');
    Route::post('/wishlist/{product}', [StoreController::class, 'wishlist'])->name('wishlist.toggle');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/dashboard/orders/{order}', [DashboardController::class, 'order'])->name('orders.show');
    Route::patch('/dashboard/profile', [DashboardController::class, 'profile'])->name('profile.update');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'productForm'])->name('products.create');
    Route::post('/products', [AdminController::class, 'saveProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'productForm'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'saveProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/orders/{order}', [AdminController::class, 'order'])->whereNumber('order')->name('orders.show');
    Route::patch('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
    Route::get('/{type}', [AdminController::class, 'resource'])->whereIn('type', ['categories', 'orders', 'customers', 'coupons', 'reviews', 'inventory', 'banners', 'settings'])->name('resource');
    Route::patch('/settings', [AdminController::class, 'saveSettings'])->name('settings.update');
    Route::post('/{type}', [AdminController::class, 'quickStore'])->whereIn('type', ['categories', 'coupons', 'banners'])->name('resource.store');
    Route::patch('/{type}/{id}', [AdminController::class, 'action'])->whereIn('type', ['reviews', 'inventory'])->name('resource.action');
});
