<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /** Image pools, filled from public/images/products at runtime. */
    protected array $imagePool = [];

    public function run(): void
    {
        $data = require database_path('data/catalog.php');

        $brands = Brand::pluck('id', 'slug');
        $categories = Category::pluck('id', 'slug');

        $this->imagePool = $this->collectImages();

        foreach ($data['products'] as $categorySlug => $items) {
            if (! isset($categories[$categorySlug])) {
                continue;
            }

            foreach ($items as $item) {
                $this->createProduct($item, (int) $categories[$categorySlug], $categorySlug, $brands);
            }
        }

        // Filler catalogue so the storefront has a believable amount of stock.
        $this->seedFillers($categories, $brands);
    }

    /* ------------------------------------------------------------------ */

    protected function createProduct(array $item, int $categoryId, string $categorySlug, $brands, string $suffix = ''): Product
    {
        $price = (int) $item['price'];
        $discount = (int) ($item['discount'] ?? 0);
        $compare = $discount > 0 ? (int) round($price / (1 - $discount / 100)) : null;

        $product = Product::create([
            'category_id' => $categoryId,
            'brand_id' => $brands[$item['brand']] ?? null,
            'name' => $item['name'].$suffix,
            'name_en' => $item['name_en'] ?? null,
            'subtitle' => $item['subtitle'] ?? null,
            'short_description' => $item['subtitle'] ?? null,
            'description' => $this->description($item),
            'price' => $price,
            'compare_at_price' => $compare,
            'discount_percent' => $discount,
            'stock' => $this->stock(),
            'max_per_order' => random_int(2, 5),
            'warranty' => $item['warranty'] ?? '۱۲ ماه گارانتی',
            'shipping_weight' => random_int(200, 8000),
            'highlights' => $item['highlights'] ?? [],
            'rating' => 0,
            'sold_count' => random_int(0, 1400),
            'views_count' => random_int(120, 48000),
            'is_active' => true,
            'is_featured' => random_int(1, 100) <= 22,
            'is_special' => random_int(1, 100) <= 14,
            'is_digino_seller' => random_int(1, 100) <= 70,
            'has_pickup' => random_int(1, 100) <= 35,
            'free_shipping' => $price >= 20_000_000 || random_int(1, 100) <= 15,
            'special_ends_at' => null,
            'meta_title' => $item['name'].' | خرید اینترنتی از دیجی‌نو',
            'meta_description' => Str::limit(strip_tags($item['subtitle'] ?? $item['name']), 150),
        ]);

        if ($product->is_special) {
            $product->update(['special_ends_at' => now()->addHours(random_int(6, 72))]);
        }

        $this->attachImages($product, $categorySlug);
        $this->attachAttributes($product, $item);
        $this->attachVariants($product, $item);

        return $product;
    }

    protected function description(array $item): string
    {
        $name = $item['name'];
        $highlights = collect($item['highlights'] ?? [])->map(fn ($h) => '• '.$h)->implode("\n");

        return implode("\n\n", array_filter([
            "{$name} یکی از گزینه‌های محبوب دیجی‌نو در دسته خود است و برای کسانی طراحی شده که به دنبال ترکیبی از کیفیت ساخت، کارایی روزمره و قیمت منصفانه هستند.",
            $item['subtitle'] ?? null,
            $highlights ? "ویژگی‌های شاخص این کالا:\n".$highlights : null,
            'تمام کالاهای دیجی‌نو پیش از ارسال از نظر سلامت فیزیکی و اصالت بررسی می‌شوند و در صورت مغایرت، امکان بازگشت کالا تا هفت روز پس از تحویل فراهم است.',
            'برای انتخاب دقیق‌تر، پیشنهاد می‌کنیم بخش مشخصات فنی و دیدگاه خریداران این کالا را مطالعه کنید.',
        ]));
    }

    protected function stock(): int
    {
        return match (random_int(1, 12)) {
            1 => 0,                       // ناموجود
            2, 3 => random_int(1, 5),     // رو به اتمام
            default => random_int(6, 120),
        };
    }

    protected function attachImages(Product $product, string $categorySlug): void
    {
        $pool = $this->imagePool[$categorySlug] ?? $this->imagePool['default'] ?? ['images/placeholder-product.svg'];

        $count = min(count($pool), random_int(2, 4));
        $picked = collect($pool)->shuffle()->take($count)->values();

        foreach ($picked as $i => $path) {
            $product->images()->create([
                'path' => $path,
                'alt' => $product->name,
                'is_primary' => $i === 0,
                'sort_order' => $i,
            ]);
        }
    }

    protected function attachAttributes(Product $product, array $item): void
    {
        $sort = 0;

        foreach (($item['specs'] ?? []) as $group => $rows) {
            foreach ($rows as $name => $value) {
                $product->attributes()->create([
                    'group' => $group,
                    'name' => $name,
                    'value' => $value,
                    'is_key' => $sort < 4,
                    'sort_order' => $sort++,
                ]);
            }
        }
    }

    protected function attachVariants(Product $product, array $item): void
    {
        $colors = $item['colors'] ?? [];
        $option = $item['options'] ?? null;
        $sort = 0;

        if ($colors && $option) {
            foreach ($colors as $colorName => $hex) {
                foreach ($option['values'] as $value => $diff) {
                    $product->variants()->create([
                        'title' => "{$colorName} / {$value}",
                        'color_name' => $colorName,
                        'color_hex' => $hex,
                        'option_name' => $option['name'],
                        'option_value' => $value,
                        'price_diff' => (int) $diff,
                        'stock' => random_int(0, 25),
                        'is_active' => true,
                        'sort_order' => $sort++,
                    ]);
                }
            }

            return;
        }

        foreach ($colors as $colorName => $hex) {
            $product->variants()->create([
                'title' => $colorName,
                'color_name' => $colorName,
                'color_hex' => $hex,
                'price_diff' => 0,
                'stock' => random_int(0, 30),
                'is_active' => true,
                'sort_order' => $sort++,
            ]);
        }

        if (! $colors && $option) {
            foreach ($option['values'] as $value => $diff) {
                $product->variants()->create([
                    'title' => $value,
                    'option_name' => $option['name'],
                    'option_value' => $value,
                    'price_diff' => (int) $diff,
                    'stock' => random_int(0, 25),
                    'is_active' => true,
                    'sort_order' => $sort++,
                ]);
            }
        }
    }

    /**
     * Extra, procedurally-named products so category pages, filters and
     * pagination all have a realistic amount of data to work with.
     */
    protected function seedFillers($categories, $brands): void
    {
        $templates = [
            'mobile-accessory' => [
                'brands' => ['anker', 'xiaomi', 'samsung'],
                'names' => [
                    'قاب محافظ گوشی مدل %s', 'محافظ صفحه نمایش شیشه‌ای مدل %s',
                    'هولدر رومیزی موبایل مدل %s', 'کابل تبدیل USB-C مدل %s',
                    'شارژر فندکی خودرو مدل %s', 'پایه نگهدارنده تبلت مدل %s',
                ],
                'price' => [250_000, 2_400_000],
                'specs' => ['مشخصات' => ['جنس بدنه' => 'پلی‌کربنات و TPU', 'گارانتی' => 'اصالت کالا']],
            ],
            'stationery' => [
                'brands' => ['kenko'],
                'names' => [
                    'خودکار روان‌نویس مدل %s', 'ست ۱۲ عددی مداد رنگی مدل %s',
                    'دفتر ۸۰ برگ طرح %s', 'پوشه دکمه‌دار A4 مدل %s',
                    'ماشین حساب مهندسی مدل %s',
                ],
                'price' => [45_000, 950_000],
                'specs' => ['مشخصات' => ['کاربرد' => 'تحصیلی و اداری']],
            ],
            'sport-gear' => [
                'brands' => ['kenko', 'nike', 'adidas'],
                'names' => [
                    'کش مقاومتی تمرین مدل %s', 'طناب ورزشی مدل %s',
                    'قمقمه ورزشی مدل %s', 'ساک ورزشی مدل %s', 'مچ‌بند تمرینی مدل %s',
                ],
                'price' => [120_000, 1_800_000],
                'specs' => ['مشخصات' => ['مناسب برای' => 'تمرین در خانه و باشگاه']],
            ],
            'kitchen-appliance' => [
                'brands' => ['bosch', 'lg', 'snowa'],
                'names' => [
                    'چای‌ساز مدل %s', 'پلوپز دیجیتال مدل %s', 'همزن برقی مدل %s',
                    'آبمیوه‌گیری مدل %s', 'توستر نان مدل %s', 'غذاساز چندکاره مدل %s',
                ],
                'price' => [2_800_000, 26_000_000],
                'specs' => ['مشخصات' => ['ولتاژ' => '۲۲۰ ولت']],
            ],
            'grocery' => [
                'brands' => ['delster'],
                'names' => [
                    'چای سیاه ممتاز بسته %s', 'عسل طبیعی %s', 'زعفران سرگل بسته %s',
                    'خرمای مضافتی %s', 'رب گوجه‌فرنگی %s', 'ماکارونی فرمی %s',
                ],
                'price' => [70_000, 1_500_000],
                'specs' => ['مشخصات' => ['نوع بسته‌بندی' => 'بهداشتی']],
            ],
            'tshirt' => [
                'brands' => ['nike', 'adidas', 'puma'],
                'names' => [
                    'تی‌شرت نخی طرح %s', 'هودی کلاه‌دار مدل %s',
                    'سویشرت ورزشی مدل %s', 'شلوار گرمکن مدل %s',
                ],
                'price' => [900_000, 4_800_000],
                'specs' => ['مشخصات' => ['جنس' => 'نخ پنبه', 'شست‌وشو' => 'ماشین لباسشویی با آب سرد']],
                'sizes' => ['S', 'M', 'L', 'XL'],
            ],
            'sneakers' => [
                'brands' => ['nike', 'adidas', 'puma'],
                'names' => [
                    'کفش پیاده‌روی مدل %s', 'کفش بسکتبال مدل %s',
                    'کفش تمرین مدل %s', 'کتانی روزمره مدل %s',
                ],
                'price' => [1_800_000, 6_200_000],
                'specs' => ['مشخصات' => ['جنس زیره' => 'لاستیک فشرده']],
                'sizes' => ['۴۰', '۴۱', '۴۲', '۴۳', '۴۴'],
            ],
            'book' => [
                'brands' => ['cheshmeh'],
                'names' => [
                    'کتاب %s (چاپ جدید)', 'رمان %s', 'کتاب صوتی-متنی %s', 'مجموعه داستان %s',
                ],
                'price' => [120_000, 650_000],
                'specs' => ['مشخصات' => ['نوع جلد' => 'شومیز']],
            ],
            'car-accessory' => [
                'brands' => ['ronix', 'anker'],
                'names' => [
                    'کفپوش سه‌بعدی خودرو مدل %s', 'روکش صندلی مدل %s',
                    'دوربین ثبت وقایع مدل %s', 'جارو دستی خودرو مدل %s',
                ],
                'price' => [400_000, 6_800_000],
                'specs' => ['مشخصات' => ['سازگاری' => 'اکثر خودروهای داخلی']],
            ],
            'tools' => [
                'brands' => ['ronix', 'bosch'],
                'names' => [
                    'فرز آهنگری مدل %s', 'اره عمودبر مدل %s', 'متر لیزری مدل %s',
                    'جعبه ابزار فلزی مدل %s', 'سنگ رومیزی مدل %s',
                ],
                'price' => [800_000, 14_000_000],
                'specs' => ['مشخصات' => ['ساخت' => 'استاندارد صنعتی']],
            ],
            'headphone' => [
                'brands' => ['jbl', 'anker', 'xiaomi', 'sony'],
                'names' => [
                    'هندزفری بلوتوثی مدل %s', 'هدفون گیمینگ مدل %s',
                    'اسپیکر بلوتوثی قابل حمل مدل %s', 'هندزفری سیمی مدل %s',
                ],
                'price' => [450_000, 9_500_000],
                'specs' => ['مشخصات' => ['نوع اتصال' => 'بلوتوث ۵.۳']],
            ],
            'smart-watch' => [
                'brands' => ['xiaomi', 'samsung', 'casio'],
                'names' => [
                    'ساعت هوشمند مدل %s', 'مچ‌بند سلامتی مدل %s', 'بند سیلیکونی ساعت مدل %s',
                ],
                'price' => [350_000, 12_000_000],
                'specs' => ['مشخصات' => ['مقاومت' => 'IP68']],
            ],
        ];

        $codes = ['A1', 'A2', 'B3', 'C5', 'D7', 'E9', 'F2', 'G4', 'H6', 'K8', 'L3', 'M5', 'N7', 'P9', 'R1', 'S4', 'T6', 'V8', 'X2', 'Z5'];

        foreach ($templates as $slug => $config) {
            if (! isset($categories[$slug])) {
                continue;
            }

            foreach ($config['names'] as $pattern) {
                foreach (collect($codes)->shuffle()->take(3) as $code) {
                    $price = random_int($config['price'][0], $config['price'][1]);
                    $price = (int) (round($price / 100_000) * 100_000);

                    $item = [
                        'name' => sprintf($pattern, $code),
                        'name_en' => null,
                        'brand' => collect($config['brands'])->random(),
                        'price' => $price,
                        'discount' => collect([0, 0, 5, 8, 12, 15, 18, 22, 27, 33])->random(),
                        'subtitle' => 'ارسال سریع از انبار دیجی‌نو',
                        'warranty' => '۱۲ ماه ضمانت اصالت و سلامت کالا',
                        'highlights' => ['بسته‌بندی استاندارد', 'ضمانت اصالت کالا', 'قابلیت بازگشت تا ۷ روز'],
                        'specs' => $config['specs'],
                        'colors' => [],
                    ];

                    if (isset($config['sizes'])) {
                        $item['options'] = [
                            'name' => 'سایز',
                            'values' => collect($config['sizes'])->mapWithKeys(fn ($s) => [$s => 0])->all(),
                        ];
                    }

                    $this->createProduct($item, (int) $categories[$slug], $slug, $brands);
                }
            }
        }
    }

    /**
     * Map every downloaded image under public/images/products/<category>-*.jpg
     * to its category, falling back to the shared pool.
     */
    protected function collectImages(): array
    {
        $pool = ['default' => []];
        $dir = public_path('images/products');

        if (! is_dir($dir)) {
            return $pool;
        }

        foreach (glob($dir.'/*.{jpg,jpeg,png,webp,svg}', GLOB_BRACE) ?: [] as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $slug = preg_replace('/-\d+$/', '', $name);
            $path = 'images/products/'.basename($file);

            $pool[$slug][] = $path;
            $pool['default'][] = $path;
        }

        return $pool;
    }
}
