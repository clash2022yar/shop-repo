<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/products', [StoreController::class, 'products']);
Route::get('/search', [StoreController::class, 'products']);
Route::get('/categories', [StoreController::class, 'categories']);
Route::get('/category/{slug}', [StoreController::class, 'products']);
Route::get('/product/{slug}', [StoreController::class, 'product']);
Route::get('/api/suggest', [StoreController::class, 'suggest'])->middleware('throttle:60,1');
Route::get('/about', [StoreController::class, 'about']);
Route::get('/help/{page?}', [StoreController::class, 'help']);
Route::get('/journal/{slug?}', [StoreController::class, 'journal']);
Route::post('/newsletter', [StoreController::class, 'subscribe'])->middleware('throttle:5,1');
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/{product}', [CartController::class, 'add'])->whereNumber('product')->block(10, 10);
Route::patch('/cart/{product}', [CartController::class, 'update'])->whereNumber('product')->block(10, 10);
Route::post('/cart/coupon', [CartController::class, 'coupon'])->middleware('throttle:10,1')->block(10, 10);
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::get('/register', [AuthController::class, 'show']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/checkout', [CartController::class, 'checkout']);
    Route::post('/checkout', [CartController::class, 'place'])->middleware('throttle:10,1')->block(10, 10);
    Route::get('/account/orders/{order}', [AccountController::class, 'order']);
    Route::post('/account/orders/{order}/cancel', [AccountController::class, 'cancel']);
    Route::get('/account/{section?}', [AccountController::class, 'index']);
    Route::post('/favorites/{product}', [AccountController::class, 'favorite']);
    Route::post('/reviews/{product}', [StoreController::class, 'review'])->middleware('throttle:5,1');
    Route::post('/account/addresses', [AccountController::class, 'address']);
    Route::put('/account/addresses/{id}', [AccountController::class, 'address']);
    Route::delete('/account/addresses/{id}', [AccountController::class, 'deleteAddress']);
    Route::put('/account/profile', [AccountController::class, 'profile']);
    Route::put('/account/password', [AccountController::class, 'password']);
    Route::post('/account/tickets', [AccountController::class, 'ticket'])->middleware('throttle:5,1');
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard']);
        Route::get('/products', [AdminController::class, 'products']);
        Route::get('/products/create', [AdminController::class, 'productForm']);
        Route::get('/products/{product}/edit', [AdminController::class, 'productForm']);
        Route::post('/products', [AdminController::class, 'saveProduct']);
        Route::put('/products/{product}', [AdminController::class, 'saveProduct']);
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct']);
        Route::get('/orders', [AdminController::class, 'orders']);
        Route::get('/orders/{order}', [AdminController::class, 'order']);
        Route::put('/orders/{order}', [AdminController::class, 'orderStatus']);
        Route::get('/settings', [AdminController::class, 'settings']);
        Route::put('/settings', [AdminController::class, 'saveSettings']);
        Route::post('/upload', [AdminController::class, 'upload']);
        Route::get('/{resource}', [AdminController::class, 'resource']);
        Route::post('/{resource}', [AdminController::class, 'saveResource']);
        Route::put('/{resource}/{id}', [AdminController::class, 'saveResource']);
        Route::delete('/{resource}/{id}', [AdminController::class, 'deleteResource']);
    });
});
