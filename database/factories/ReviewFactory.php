<?php

namespace Database\Factories;

use App\Enums\ReviewStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'title' => 'دیدگاه خریدار',
            'body' => 'کیفیت ساخت مناسبی دارد و ارسال هم به‌موقع انجام شد.',
            'rating' => fake()->numberBetween(1, 5),
            'pros' => ['کیفیت ساخت'],
            'cons' => [],
            'recommends' => true,
            'status' => ReviewStatus::Approved->value,
            'is_buyer' => true,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => ReviewStatus::Pending->value]);
    }
}
