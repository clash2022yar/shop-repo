<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $price = fake()->numberBetween(5, 900) * 1_000_000;
        $discount = fake()->randomElement([0, 0, 5, 10, 15, 20, 30]);

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => 'کالای آزمایشی '.Str::upper(Str::random(5)),
            'price' => $price,
            'compare_at_price' => $discount ? (int) round($price / (1 - $discount / 100)) : null,
            'discount_percent' => $discount,
            'stock' => fake()->numberBetween(0, 80),
            'max_per_order' => 5,
            'warranty' => '۱۲ ماه گارانتی',
            'highlights' => ['ضمانت اصالت کالا', 'ارسال سریع'],
            'is_active' => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function special(): static
    {
        return $this->state(fn () => [
            'is_special' => true,
            'special_ends_at' => now()->addDay(),
            'discount_percent' => 25,
        ]);
    }
}
