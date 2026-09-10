<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\CustomerAdminController;
use App\Http\Controllers\Admin\CouponAdminController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\Admin\BannerAdminController;
use App\Http\Controllers\Admin\SettingAdminController;
use App\Http\Controllers\Admin\InventoryAdminController;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/about', fn() => view('front.about'))->name('about');
Route::get('/contact', fn() => view('front.contact'))->name('contact');

// Cart AJAX
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

// Auth (AJAX)
Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Checkout
Route::middleware('auth')->group(function(){
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// User Dashboard (header only, no footer)
Route::middleware('auth')->prefix('dashboard')->name('user.')->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/wishlist', [DashboardController::class, 'wishlist'])->name('wishlist');
    Route::get('/addresses', [DashboardController::class, 'addresses'])->name('addresses');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
});

// Admin (header only)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    // Products
    Route::resource('products', ProductAdminController::class);
    Route::get('inventory', [InventoryAdminController::class, 'index'])->name('inventory.index');
    Route::patch('inventory/{product}', [InventoryAdminController::class, 'update'])->name('inventory.update');
    // Categories
    Route::resource('categories', CategoryAdminController::class);
    // Orders
    Route::get('orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderAdminController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('orders.status');
    // Customers
    Route::get('customers', [CustomerAdminController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [CustomerAdminController::class, 'show'])->name('customers.show');
    // Coupons
    Route::resource('coupons', CouponAdminController::class);
    // Reviews
    Route::get('reviews', [ReviewAdminController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}', [ReviewAdminController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [ReviewAdminController::class, 'destroy'])->name('reviews.destroy');
    // Banners
    Route::resource('banners', BannerAdminController::class);
    // Settings
    Route::get('settings', [SettingAdminController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingAdminController::class, 'update'])->name('settings.update');
});

Route::get('/up', fn() => response()->json(['status' => 'ok', 'app' => 'Digino', 'version' => '1.0', 'php' => '8.3.28']));
