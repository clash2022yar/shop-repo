<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['کالای دیجیتال', 'لوازم خانگی', 'مد و پوشاک', 'ورزش و سفر', 'کتاب']).' '.fake()->numberBetween(1, 999);

        return [
            'name' => $name,
            'slug' => 'cat-'.Str::lower(Str::random(8)),
            'icon' => 'grid',
            'is_active' => true,
            'show_in_menu' => true,
            'sort_order' => 0,
        ];
    }
}
