<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('data/catalog.php');

        // ------------------------------------------------------ categories
        foreach ($data['categories'] as $sort => $root) {
            $parent = Category::create([
                'name' => $root['name'],
                'slug' => $root['slug'],
                'icon' => $root['icon'],
                'description' => "خرید اینترنتی انواع {$root['name']} با بهترین قیمت و ضمانت اصالت کالا از دیجی‌نو.",
                'is_active' => true,
                'show_in_menu' => true,
                'sort_order' => $sort,
                'meta_title' => "{$root['name']} | دیجی‌نو",
                'meta_description' => "مقایسه و خرید {$root['name']} با امکان پرداخت در محل و ارسال سریع.",
            ]);

            foreach ($root['children'] ?? [] as $childSort => $child) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $child['name'],
                    'slug' => $child['slug'],
                    'icon' => $child['icon'],
                    'description' => "جدیدترین مدل‌های {$child['name']} با قیمت روز و ضمانت دیجی‌نو.",
                    'is_active' => true,
                    'show_in_menu' => true,
                    'sort_order' => $childSort,
                    'meta_title' => "{$child['name']} | دیجی‌نو",
                    'meta_description' => "خرید {$child['name']} با گارانتی معتبر و ارسال سریع از دیجی‌نو.",
                ]);
            }
        }

        // ---------------------------------------------------------- brands
        foreach ($data['brands'] as $sort => $brand) {
            Brand::create([
                'name' => $brand['name'],
                'name_en' => $brand['name_en'],
                'slug' => $brand['slug'],
                'logo' => file_exists(public_path("images/brands/{$brand['slug']}.svg"))
                    ? "images/brands/{$brand['slug']}.svg"
                    : null,
                'description' => "کالاهای اصل {$brand['name']} با ضمانت اصالت، در دیجی‌نو.",
                'is_featured' => (bool) ($brand['featured'] ?? false),
                'is_active' => true,
                'sort_order' => $sort,
            ]);
        }

        // ------------------------------------------------- shipping methods
        foreach ($data['shipping'] as $sort => $method) {
            ShippingMethod::create($method + ['sort_order' => $sort, 'is_active' => true]);
        }
    }
}
