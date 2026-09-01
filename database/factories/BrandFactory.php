<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    public function definition(): array
    {
        $en = ucfirst(Str::lower(Str::random(6)));

        return [
            'name' => $en,
            'name_en' => $en,
            'slug' => Str::slug($en).'-'.Str::lower(Str::random(4)),
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }
}
