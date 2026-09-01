<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon', 60)->nullable();      // key of an inline SVG icon
            $table->string('image')->nullable();
            $table->string('banner')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('show_in_menu')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->string('sku', 40)->unique();
            $table->string('subtitle')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('price');                       // Toman
            $table->unsignedBigInteger('compare_at_price')->nullable(); // strike-through price
            $table->unsignedTinyInteger('discount_percent')->default(0)->index();
            $table->unsignedInteger('stock')->default(0)->index();
            $table->unsignedTinyInteger('max_per_order')->default(5);
            $table->string('warranty')->nullable();
            $table->unsignedInteger('shipping_weight')->default(0); // grams
            $table->json('highlights')->nullable();
            $table->json('specs')->nullable();
            $table->decimal('rating', 3, 2)->default(0)->index();
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedInteger('questions_count')->default(0);
            $table->unsignedInteger('sold_count')->default(0)->index();
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_special')->default(false)->index();  // "فروش ویژه"
            $table->boolean('is_digino_seller')->default(true)->index();
            $table->boolean('has_pickup')->default(false);
            $table->boolean('free_shipping')->default(false);
            $table->timestamp('special_ends_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'category_id']);
            $table->index(['is_active', 'price']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('title');                      // "مشکی / 256 گیگابایت"
            $table->string('color_name', 60)->nullable();
            $table->string('color_hex', 9)->nullable();
            $table->string('option_name', 60)->nullable(); // e.g. "حافظه داخلی"
            $table->string('option_value', 60)->nullable();// e.g. "256 گیگابایت"
            $table->string('sku', 50)->unique();
            $table->bigInteger('price_diff')->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('group', 80)->default('مشخصات کلی');
            $table->string('name', 120);
            $table->string('value', 500);
            $table->boolean('is_key')->default(false); // shown in the quick-spec strip
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};
