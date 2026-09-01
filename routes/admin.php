<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Digino — admin panel  (prefix: /admin, middleware: web, auth, admin)
|--------------------------------------------------------------------------
|
| Index pages render a full Blade page; every create/update/delete action is
| an AJAX endpoint that answers with JSON so the panel never full-reloads.
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/chart', [DashboardController::class, 'chart'])->name('dashboard.chart');
Route::get('/dashboard/activity', [DashboardController::class, 'activity'])->name('dashboard.activity');

// ---------------------------------------------------------------- products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
Route::post('/products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
Route::post('/products/bulk', [ProductController::class, 'bulk'])->name('products.bulk');
Route::get('/products/table', [ProductController::class, 'table'])->name('products.table');

// -------------------------------------------------------------- categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

// ------------------------------------------------------------------ brands
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

// ------------------------------------------------------------------ orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/table', [OrderController::class, 'table'])->name('orders.table');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
Route::post('/orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.tracking');
Route::post('/orders/{order}/note', [OrderController::class, 'updateNote'])->name('orders.note');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

// --------------------------------------------------------------- customers
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/table', [CustomerController::class, 'table'])->name('customers.table');
Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/{user}', [CustomerController::class, 'update'])->name('customers.update');
Route::post('/customers/{user}/toggle', [CustomerController::class, 'toggle'])->name('customers.toggle');
Route::delete('/customers/{user}', [CustomerController::class, 'destroy'])->name('customers.destroy');

// ----------------------------------------------------------------- coupons
Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');
Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
Route::post('/coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');

// ----------------------------------------------------------------- reviews
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::get('/questions', [ReviewController::class, 'questions'])->name('questions.index');
Route::post('/questions/{question}/answer', [ReviewController::class, 'answer'])->name('questions.answer');
Route::post('/questions/{question}/approve', [ReviewController::class, 'approveQuestion'])->name('questions.approve');
Route::delete('/questions/{question}', [ReviewController::class, 'destroyQuestion'])->name('questions.destroy');

// --------------------------------------------------------------- inventory
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/inventory/table', [InventoryController::class, 'table'])->name('inventory.table');
Route::post('/inventory/{product}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
Route::get('/inventory/{product}/history', [InventoryController::class, 'history'])->name('inventory.history');
Route::get('/inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements');

// ----------------------------------------------------------------- banners
Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
Route::get('/banners/{banner}', [BannerController::class, 'show'])->name('banners.show');
Route::put('/banners/{banner}', [BannerController::class, 'update'])->name('banners.update');
Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
Route::post('/banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');

// -------------------------------------------------------- blog & CMS pages
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
Route::get('/pages/{page}', [PageController::class, 'show'])->name('pages.show');
Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

// ----------------------------------------------------------------- tickets
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
Route::post('/tickets/{ticket}/status', [TicketController::class, 'status'])->name('tickets.status');

// ---------------------------------------------------------------- shipping
Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');
Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
Route::get('/shipping/{method}', [ShippingController::class, 'show'])->name('shipping.show');
Route::put('/shipping/{method}', [ShippingController::class, 'update'])->name('shipping.update');
Route::delete('/shipping/{method}', [ShippingController::class, 'destroy'])->name('shipping.destroy');

// ----------------------------------------------------------------- reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('/reports/top-products', [ReportController::class, 'topProducts'])->name('reports.top-products');
Route::get('/reports/searches', [ReportController::class, 'searches'])->name('reports.searches');

// ------------------------------------------------------- staff & settings
Route::middleware('can:manage-staff')->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::put('/staff/{user}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
Route::post('/settings/cache/clear', [SettingController::class, 'clearCache'])->name('settings.cache.clear');
