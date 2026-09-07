<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('icon')->nullable();
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('sku')->unique();
            $t->text('short_description')->nullable();
            $t->longText('description')->nullable();
            $t->unsignedBigInteger('price');
            $t->unsignedBigInteger('old_price')->nullable();
            $t->unsignedInteger('stock')->default(0);
            $t->decimal('rating', 2, 1)->default(4.5);
            $t->unsignedInteger('reviews_count')->default(0);
            $t->string('brand')->nullable();
            $t->string('image');
            $t->json('gallery')->nullable();
            $t->json('specifications')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('coupons', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->enum('type', ['percent', 'fixed'])->default('percent');
            $t->unsignedInteger('value');
            $t->unsignedInteger('min_order')->default(0);
            $t->unsignedInteger('usage_limit')->nullable();
            $t->unsignedInteger('used_count')->default(0);
            $t->dateTime('expires_at')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('number')->unique();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $t->unsignedBigInteger('subtotal');
            $t->unsignedBigInteger('discount')->default(0);
            $t->unsignedBigInteger('shipping')->default(0);
            $t->unsignedBigInteger('total');
            $t->string('coupon_code')->nullable();
            $t->string('payment_method')->default('online');
            $t->string('payment_status')->default('paid');
            $t->json('address');
            $t->text('note')->nullable();
            $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('product_name');
            $t->string('product_image');
            $t->unsignedInteger('quantity');
            $t->unsignedBigInteger('price');
            $t->unsignedBigInteger('total');
            $t->timestamps();
        });
        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('rating');
            $t->text('body');
            $t->boolean('is_approved')->default(false);
            $t->timestamps();
            $t->unique(['user_id', 'product_id']);
        });
        Schema::create('banners', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->string('image')->nullable();
            $t->string('url')->default('/products');
            $t->string('position')->default('home');
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
        Schema::create('addresses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('recipient');
            $t->string('phone');
            $t->string('province');
            $t->string('city');
            $t->text('address');
            $t->string('postal_code');
            $t->boolean('is_default')->default(false);
            $t->timestamps();
        });
        Schema::create('wishlists', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->timestamps();
            $t->unique(['user_id', 'product_id']);
        });
        Schema::create('newsletter_subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->timestamp('subscribed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        foreach (['newsletter_subscribers', 'wishlists', 'addresses', 'settings', 'banners', 'reviews', 'order_items', 'orders', 'coupons', 'products', 'categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
