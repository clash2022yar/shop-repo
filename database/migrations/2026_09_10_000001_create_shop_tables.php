<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->boolean('is_admin')->default(false);
            $t->string('phone')->nullable();
        });
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('image')->nullable();
            $t->string('icon')->default('box');
            $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->restrictOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('brand');
            $t->string('sku')->unique();
            $t->text('description');
            $t->unsignedBigInteger('price');
            $t->unsignedBigInteger('old_price')->nullable();
            $t->unsignedInteger('stock')->default(0);
            $t->string('image');
            $t->json('images')->nullable();
            $t->json('specs')->nullable();
            $t->string('color')->default('مشکی');
            $t->boolean('featured')->default(false);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->index(['active', 'category_id']);
        });
        Schema::create('coupons', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->unsignedTinyInteger('percent');
            $t->unsignedBigInteger('min_total')->default(0);
            $t->unsignedInteger('usage_limit')->default(100);
            $t->unsignedInteger('used')->default(0);
            $t->date('expires_at');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('addresses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('phone');
            $t->string('city');
            $t->string('postal_code');
            $t->text('address');
            $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->restrictOnDelete();
            $t->string('number')->unique();
            $t->string('checkout_token')->unique();
            $t->string('status')->default('processing');
            $t->unsignedBigInteger('subtotal');
            $t->unsignedBigInteger('discount')->default(0);
            $t->unsignedBigInteger('shipping')->default(0);
            $t->unsignedBigInteger('total');
            $t->json('address');
            $t->string('payment_method')->default('cod');
            $t->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $t->text('note')->nullable();
            $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->string('image');
            $t->unsignedBigInteger('price');
            $t->unsignedInteger('quantity');
            $t->timestamps();
        });
        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('rating');
            $t->text('body');
            $t->boolean('approved')->default(false);
            $t->timestamps();
            $t->unique(['user_id', 'product_id']);
        });
        Schema::create('wishlists', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->timestamps();
            $t->unique(['user_id', 'product_id']);
        });
        Schema::create('banners', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('subtitle');
            $t->string('image');
            $t->string('link');
            $t->string('color')->default('#f1f3f6');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('image');
            $t->text('excerpt');
            $t->longText('body');
            $t->boolean('published')->default(true);
            $t->timestamps();
        });
        Schema::create('tickets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('subject');
            $t->text('body');
            $t->text('reply')->nullable();
            $t->string('status')->default('open');
            $t->timestamps();
        });
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
        Schema::create('subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['subscribers', 'settings', 'tickets', 'articles', 'banners', 'wishlists', 'reviews', 'order_items', 'orders', 'addresses', 'coupons', 'products', 'categories'] as $table) {
            Schema::dropIfExists($table);
        }Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['is_admin', 'phone']));
    }
};
