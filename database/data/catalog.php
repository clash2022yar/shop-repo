<?php

/*
|--------------------------------------------------------------------------
| دیجی‌نو — catalog seed data
|--------------------------------------------------------------------------
|
| Static, hand-written Persian catalogue data used by the database seeders.
| Keeping it in a plain PHP array (instead of inside the seeder classes) makes
| the data easy to extend without touching any logic.
|
| Prices are stored in ریال (the app formats them as تومان by dividing by 10).
|
*/

return [

    /*
    |----------------------------------------------------------------------
    | Categories — two levels, exactly the way the mega-menu renders them
    |----------------------------------------------------------------------
    */
    'categories' => [
        [
            'name' => 'کالای دیجیتال', 'slug' => 'digital', 'icon' => 'mobile',
            'children' => [
                ['name' => 'گوشی موبایل', 'slug' => 'mobile-phone', 'icon' => 'mobile'],
                ['name' => 'لپ‌تاپ و اولترابوک', 'slug' => 'laptop', 'icon' => 'laptop'],
                ['name' => 'تبلت', 'slug' => 'tablet', 'icon' => 'tablet'],
                ['name' => 'ساعت و مچ‌بند هوشمند', 'slug' => 'smart-watch', 'icon' => 'watch'],
                ['name' => 'هدفون، هدست و هندزفری', 'slug' => 'headphone', 'icon' => 'headphone'],
                ['name' => 'دوربین عکاسی', 'slug' => 'camera', 'icon' => 'camera'],
                ['name' => 'کنسول و بازی', 'slug' => 'gaming', 'icon' => 'gamepad'],
                ['name' => 'لوازم جانبی موبایل', 'slug' => 'mobile-accessory', 'icon' => 'cable'],
            ],
        ],
        [
            'name' => 'لوازم خانگی', 'slug' => 'home-appliance', 'icon' => 'home',
            'children' => [
                ['name' => 'یخچال و فریزر', 'slug' => 'refrigerator', 'icon' => 'fridge'],
                ['name' => 'ماشین لباسشویی', 'slug' => 'washing-machine', 'icon' => 'washer'],
                ['name' => 'جاروبرقی', 'slug' => 'vacuum', 'icon' => 'vacuum'],
                ['name' => 'لوازم آشپزخانه برقی', 'slug' => 'kitchen-appliance', 'icon' => 'kitchen'],
            ],
        ],
        [
            'name' => 'مد و پوشاک', 'slug' => 'fashion', 'icon' => 'shirt',
            'children' => [
                ['name' => 'کفش ورزشی', 'slug' => 'sneakers', 'icon' => 'shoe'],
                ['name' => 'تی‌شرت و پولوشرت', 'slug' => 'tshirt', 'icon' => 'shirt'],
                ['name' => 'کیف و کوله‌پشتی', 'slug' => 'bag', 'icon' => 'bag'],
                ['name' => 'ساعت مچی', 'slug' => 'wrist-watch', 'icon' => 'watch'],
            ],
        ],
        [
            'name' => 'آرایشی و بهداشتی', 'slug' => 'beauty', 'icon' => 'beauty',
            'children' => [
                ['name' => 'عطر و ادکلن', 'slug' => 'perfume', 'icon' => 'perfume'],
                ['name' => 'مراقبت پوست', 'slug' => 'skin-care', 'icon' => 'beauty'],
                ['name' => 'لوازم آرایش', 'slug' => 'makeup', 'icon' => 'beauty'],
            ],
        ],
        [
            'name' => 'کتاب و لوازم تحریر', 'slug' => 'book-stationery', 'icon' => 'book',
            'children' => [
                ['name' => 'کتاب چاپی', 'slug' => 'book', 'icon' => 'book'],
                ['name' => 'لوازم تحریر', 'slug' => 'stationery', 'icon' => 'pen'],
            ],
        ],
        [
            'name' => 'ورزش و سفر', 'slug' => 'sport-travel', 'icon' => 'sport',
            'children' => [
                ['name' => 'لوازم ورزشی', 'slug' => 'sport-gear', 'icon' => 'sport'],
                ['name' => 'چمدان و ساک سفر', 'slug' => 'luggage', 'icon' => 'suitcase'],
            ],
        ],
        [
            'name' => 'خودرو و ابزار', 'slug' => 'car-tools', 'icon' => 'car',
            'children' => [
                ['name' => 'لوازم جانبی خودرو', 'slug' => 'car-accessory', 'icon' => 'car'],
                ['name' => 'ابزارآلات', 'slug' => 'tools', 'icon' => 'tools'],
            ],
        ],
        [
            'name' => 'کالای سوپرمارکتی', 'slug' => 'supermarket', 'icon' => 'food',
            'children' => [
                ['name' => 'خواروبار', 'slug' => 'grocery', 'icon' => 'food'],
                ['name' => 'نوشیدنی', 'slug' => 'beverage', 'icon' => 'cup'],
            ],
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Brands
    |----------------------------------------------------------------------
    */
    'brands' => [
        ['name' => 'سامسونگ', 'name_en' => 'Samsung', 'slug' => 'samsung', 'featured' => true],
        ['name' => 'اپل', 'name_en' => 'Apple', 'slug' => 'apple', 'featured' => true],
        ['name' => 'شیائومی', 'name_en' => 'Xiaomi', 'slug' => 'xiaomi', 'featured' => true],
        ['name' => 'ایسوس', 'name_en' => 'Asus', 'slug' => 'asus', 'featured' => true],
        ['name' => 'لنوو', 'name_en' => 'Lenovo', 'slug' => 'lenovo', 'featured' => true],
        ['name' => 'اچ‌پی', 'name_en' => 'HP', 'slug' => 'hp', 'featured' => true],
        ['name' => 'دل', 'name_en' => 'Dell', 'slug' => 'dell'],
        ['name' => 'ال‌جی', 'name_en' => 'LG', 'slug' => 'lg', 'featured' => true],
        ['name' => 'سونی', 'name_en' => 'Sony', 'slug' => 'sony', 'featured' => true],
        ['name' => 'جی‌بی‌ال', 'name_en' => 'JBL', 'slug' => 'jbl'],
        ['name' => 'انکر', 'name_en' => 'Anker', 'slug' => 'anker'],
        ['name' => 'کنون', 'name_en' => 'Canon', 'slug' => 'canon'],
        ['name' => 'نیکون', 'name_en' => 'Nikon', 'slug' => 'nikon'],
        ['name' => 'بوش', 'name_en' => 'Bosch', 'slug' => 'bosch'],
        ['name' => 'اسنوا', 'name_en' => 'Snowa', 'slug' => 'snowa'],
        ['name' => 'پاکشوما', 'name_en' => 'Pakshoma', 'slug' => 'pakshoma'],
        ['name' => 'نایک', 'name_en' => 'Nike', 'slug' => 'nike', 'featured' => true],
        ['name' => 'آدیداس', 'name_en' => 'Adidas', 'slug' => 'adidas'],
        ['name' => 'پوما', 'name_en' => 'Puma', 'slug' => 'puma'],
        ['name' => 'درسا', 'name_en' => 'Dorsa', 'slug' => 'dorsa'],
        ['name' => 'کاسیو', 'name_en' => 'Casio', 'slug' => 'casio'],
        ['name' => 'لورآل', 'name_en' => "L'Oreal", 'slug' => 'loreal'],
        ['name' => 'نیوآ', 'name_en' => 'Nivea', 'slug' => 'nivea'],
        ['name' => 'سین‌رجیس', 'name_en' => 'Sinregis', 'slug' => 'sinregis'],
        ['name' => 'نشر چشمه', 'name_en' => 'Cheshmeh', 'slug' => 'cheshmeh'],
        ['name' => 'کنکو', 'name_en' => 'Kenko', 'slug' => 'kenko'],
        ['name' => 'رونیکس', 'name_en' => 'Ronix', 'slug' => 'ronix'],
        ['name' => 'دلستر', 'name_en' => 'Delster', 'slug' => 'delster'],
    ],

    /*
    |----------------------------------------------------------------------
    | Shipping methods
    |----------------------------------------------------------------------
    */
    'shipping' => [
        [
            'name' => 'ارسال عادی پست', 'icon' => 'truck', 'cost' => 490_000, 'free_from' => 50_000_000,
            'estimated_days' => 4, 'description' => 'تحویل ۳ تا ۵ روز کاری در سراسر ایران',
        ],
        [
            'name' => 'پست پیشتاز', 'icon' => 'rocket', 'cost' => 890_000, 'free_from' => 80_000_000,
            'estimated_days' => 2, 'description' => 'تحویل ۱ تا ۳ روز کاری با کد رهگیری',
        ],
        [
            'name' => 'ارسال فوری دیجی‌نو', 'icon' => 'flash', 'cost' => 1_290_000, 'free_from' => null,
            'estimated_days' => 1, 'description' => 'تحویل در همان روز، ویژه تهران و کرج',
        ],
        [
            'name' => 'تحویل حضوری', 'icon' => 'store', 'cost' => 0, 'free_from' => 0,
            'estimated_days' => 1, 'description' => 'دریافت از مراکز دیجی‌نو بدون هزینه ارسال',
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Products — grouped by the slug of their (leaf) category
    |----------------------------------------------------------------------
    |
    | price          : تومان
    | discount       : percentage used to derive compare_at_price
    | specs          : [group => [name => value]]
    | colors         : [name => hex]
    | options        : ['name' => 'حافظه داخلی', 'values' => ['128 گیگابایت' => 0, ...]]
    |
    */
    'products' => [

        'mobile-phone' => [
            [
                'name' => 'گوشی موبایل سامسونگ مدل Galaxy S24 Ultra 5G', 'name_en' => 'Samsung Galaxy S24 Ultra',
                'brand' => 'samsung', 'price' => 68_900_000, 'discount' => 9,
                'subtitle' => 'حافظه ۲۵۶ گیگابایت و رم ۱۲ گیگابایت',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر ۶.۸ اینچی Dynamic AMOLED 2X', 'تراشه Snapdragon 8 Gen 3', 'دوربین ۲۰۰ مگاپیکسلی', 'قلم S Pen داخلی'],
                'colors' => ['مشکی تیتانیوم' => '#3B3B3D', 'خاکستری تیتانیوم' => '#8E8E93', 'بنفش تیتانیوم' => '#6F5B8E'],
                'options' => ['name' => 'حافظه داخلی', 'values' => ['۲۵۶ گیگابایت' => 0, '۵۱۲ گیگابایت' => 60_000_000, '۱ ترابایت' => 130_000_000]],
                'specs' => [
                    'مشخصات کلی' => ['ابعاد' => '۱۶۲.۳ × ۷۹ × ۸.۶ میلی‌متر', 'وزن' => '۲۳۲ گرم', 'سیم‌کارت' => 'دو سیم‌کارت نانو'],
                    'صفحه‌نمایش' => ['اندازه' => '۶.۸ اینچ', 'نوع' => 'Dynamic AMOLED 2X', 'نرخ نوسازی' => '۱۲۰ هرتز'],
                    'دوربین' => ['دوربین اصلی' => '۲۰۰ مگاپیکسل', 'دوربین سلفی' => '۱۲ مگاپیکسل', 'فیلم‌برداری' => '8K در ۳۰ فریم'],
                    'باتری' => ['ظرفیت' => '۵۰۰۰ میلی‌آمپرساعت', 'شارژ سریع' => '۴۵ وات'],
                ],
            ],
            [
                'name' => 'گوشی موبایل اپل مدل iPhone 15 Pro Max', 'name_en' => 'Apple iPhone 15 Pro Max',
                'brand' => 'apple', 'price' => 85_200_000, 'discount' => 5,
                'subtitle' => 'بدنه تیتانیومی و پورت USB-C',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['تراشه A17 Pro', 'بدنه تیتانیوم درجه ۵', 'دکمه Action قابل تنظیم', 'زوم اپتیکال ۵ برابر'],
                'colors' => ['تیتانیوم طبیعی' => '#9A968F', 'تیتانیوم آبی' => '#4C5A6A', 'تیتانیوم مشکی' => '#3A3A3C'],
                'options' => ['name' => 'حافظه داخلی', 'values' => ['۲۵۶ گیگابایت' => 0, '۵۱۲ گیگابایت' => 90_000_000, '۱ ترابایت' => 180_000_000]],
                'specs' => [
                    'مشخصات کلی' => ['ابعاد' => '۱۵۹.۹ × ۷۶.۷ × ۸.۳ میلی‌متر', 'وزن' => '۲۲۱ گرم'],
                    'صفحه‌نمایش' => ['اندازه' => '۶.۷ اینچ', 'نوع' => 'Super Retina XDR', 'نرخ نوسازی' => '۱۲۰ هرتز'],
                    'دوربین' => ['دوربین اصلی' => '۴۸ مگاپیکسل', 'تله‌فوتو' => '۱۲ مگاپیکسل ۵ برابر'],
                ],
            ],
            [
                'name' => 'گوشی موبایل شیائومی مدل Redmi Note 13 Pro', 'name_en' => 'Xiaomi Redmi Note 13 Pro',
                'brand' => 'xiaomi', 'price' => 16_800_000, 'discount' => 17,
                'subtitle' => 'دوربین ۲۰۰ مگاپیکسلی در بازه قیمتی میان‌رده',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر AMOLED با روشنایی ۱۸۰۰ نیت', 'شارژ سریع ۶۷ وات', 'مقاومت IP54'],
                'colors' => ['مشکی میدنایت' => '#1F2124', 'بنفش' => '#7B5EA7', 'سفید' => '#F1F1F3'],
                'options' => ['name' => 'حافظه داخلی', 'values' => ['۱۲۸ گیگابایت' => 0, '۲۵۶ گیگابایت' => 18_000_000]],
                'specs' => [
                    'صفحه‌نمایش' => ['اندازه' => '۶.۶۷ اینچ', 'نوع' => 'AMOLED', 'نرخ نوسازی' => '۱۲۰ هرتز'],
                    'باتری' => ['ظرفیت' => '۵۱۰۰ میلی‌آمپرساعت', 'شارژ سریع' => '۶۷ وات'],
                ],
            ],
            [
                'name' => 'گوشی موبایل سامسونگ مدل Galaxy A55 5G', 'name_en' => 'Samsung Galaxy A55',
                'brand' => 'samsung', 'price' => 23_200_000, 'discount' => 12,
                'subtitle' => 'بدنه فلزی و نمایشگر Super AMOLED',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر ۶.۶ اینچی ۱۲۰ هرتز', 'دوربین اصلی ۵۰ مگاپیکسل', 'چهار سال به‌روزرسانی اندروید'],
                'colors' => ['آبی یخی' => '#8FB3D9', 'مشکی' => '#2A2C30', 'لیمویی' => '#D8E29A'],
                'options' => ['name' => 'حافظه داخلی', 'values' => ['۱۲۸ گیگابایت' => 0, '۲۵۶ گیگابایت' => 22_000_000]],
                'specs' => ['صفحه‌نمایش' => ['اندازه' => '۶.۶ اینچ', 'نوع' => 'Super AMOLED']],
            ],
            [
                'name' => 'گوشی موبایل شیائومی مدل POCO X6 Pro', 'name_en' => 'Xiaomi POCO X6 Pro',
                'brand' => 'xiaomi', 'price' => 19_600_000, 'discount' => 21,
                'subtitle' => 'پردازنده Dimensity 8300 Ultra',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['عملکرد فوق‌العاده در بازی', 'شارژ ۶۷ وات', 'نمایشگر ۱.۵K'],
                'colors' => ['مشکی' => '#1D1E22', 'زرد' => '#E7B426'],
                'specs' => ['پردازنده' => ['تراشه' => 'Dimensity 8300 Ultra', 'رم' => '۱۲ گیگابایت']],
            ],
            [
                'name' => 'گوشی موبایل اپل مدل iPhone 13', 'name_en' => 'Apple iPhone 13',
                'brand' => 'apple', 'price' => 39_800_000, 'discount' => 8,
                'subtitle' => 'انتخابی مطمئن با تراشه A15 Bionic',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر Super Retina XDR', 'باتری با دوام', 'حالت سینمایی'],
                'colors' => ['نیمه‌شب' => '#25272B', 'صورتی' => '#F2CFCB', 'آبی' => '#3A6EA5'],
                'specs' => ['صفحه‌نمایش' => ['اندازه' => '۶.۱ اینچ']],
            ],
            [
                'name' => 'گوشی موبایل سامسونگ مدل Galaxy Z Flip5', 'name_en' => 'Samsung Galaxy Z Flip5',
                'brand' => 'samsung', 'price' => 61_200_000, 'discount' => 14,
                'subtitle' => 'گوشی تاشو با نمایشگر بیرونی ۳.۴ اینچی',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['طراحی تاشو جمع‌وجور', 'نمایشگر بیرونی Flex Window', 'مقاومت IPX8'],
                'colors' => ['نعنایی' => '#A9C5B6', 'خاکستری' => '#6E7176'],
                'specs' => ['صفحه‌نمایش' => ['اندازه' => '۶.۷ اینچ تاشو']],
            ],
            [
                'name' => 'گوشی موبایل شیائومی مدل Xiaomi 14', 'name_en' => 'Xiaomi 14',
                'brand' => 'xiaomi', 'price' => 51_200_000, 'discount' => 11,
                'subtitle' => 'دوربین Leica و تراشه Snapdragon 8 Gen 3',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['لنزهای Leica Summilux', 'شارژ ۹۰ وات', 'نمایشگر LTPO'],
                'colors' => ['سبز' => '#4C6B52', 'مشکی' => '#232427'],
                'specs' => ['پردازنده' => ['تراشه' => 'Snapdragon 8 Gen 3']],
            ],
        ],

        'laptop' => [
            [
                'name' => 'لپ‌تاپ ۱۵.۶ اینچی ایسوس مدل VivoBook 15 X1504', 'name_en' => 'Asus VivoBook 15',
                'brand' => 'asus', 'price' => 34_800_000, 'discount' => 13,
                'subtitle' => 'پردازنده Core i5 نسل ۱۲ و رم ۱۶ گیگابایت',
                'warranty' => '۲۴ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر Full HD', 'رم قابل ارتقا تا ۲۴ گیگابایت', 'وزن ۱.۷ کیلوگرم'],
                'colors' => ['نقره‌ای' => '#C7C9CC', 'مشکی' => '#26282B'],
                'options' => ['name' => 'حافظه SSD', 'values' => ['۵۱۲ گیگابایت' => 0, '۱ ترابایت' => 28_000_000]],
                'specs' => [
                    'پردازنده' => ['مدل' => 'Intel Core i5-1235U', 'تعداد هسته' => '۱۰ هسته'],
                    'حافظه' => ['رم' => '۱۶ گیگابایت DDR4', 'حافظه داخلی' => 'SSD 512GB NVMe'],
                    'نمایشگر' => ['اندازه' => '۱۵.۶ اینچ', 'رزولوشن' => '۱۹۲۰ × ۱۰۸۰'],
                ],
            ],
            [
                'name' => 'لپ‌تاپ ۱۴ اینچی اپل مدل MacBook Air M2', 'name_en' => 'Apple MacBook Air M2',
                'brand' => 'apple', 'price' => 72_800_000, 'discount' => 6,
                'subtitle' => 'تراشه M2 با ۸ هسته پردازشی',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر Liquid Retina', 'باتری تا ۱۸ ساعت', 'بدون فن و کاملاً بی‌صدا'],
                'colors' => ['خاکستری' => '#7D7E80', 'استارلایت' => '#EFE5D8', 'میدنایت' => '#2E3641'],
                'specs' => ['پردازنده' => ['تراشه' => 'Apple M2'], 'حافظه' => ['رم' => '۸ گیگابایت']],
            ],
            [
                'name' => 'لپ‌تاپ ۱۵.۶ اینچی لنوو مدل IdeaPad Gaming 3', 'name_en' => 'Lenovo IdeaPad Gaming 3',
                'brand' => 'lenovo', 'price' => 51_200_000, 'discount' => 18,
                'subtitle' => 'کارت گرافیک RTX 3050 و نمایشگر ۱۲۰ هرتز',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['سیستم خنک‌کننده دوفن', 'کیبورد بک‌لایت آبی', 'رم ۱۶ گیگابایت'],
                'colors' => ['خاکستری' => '#4B4F55'],
                'specs' => ['گرافیک' => ['مدل' => 'RTX 3050 4GB'], 'نمایشگر' => ['نرخ نوسازی' => '۱۲۰ هرتز']],
            ],
            [
                'name' => 'لپ‌تاپ ۱۴ اینچی اچ‌پی مدل ProBook 440 G10', 'name_en' => 'HP ProBook 440 G10',
                'brand' => 'hp', 'price' => 46_200_000, 'discount' => 10,
                'subtitle' => 'لپ‌تاپ اداری با بدنه آلومینیومی',
                'warranty' => '۲۴ ماه گارانتی شرکتی',
                'highlights' => ['امنیت HP Wolf', 'وب‌کم ۵ مگاپیکسلی', 'وزن ۱.۴ کیلوگرم'],
                'colors' => ['نقره‌ای' => '#B9BDC2'],
                'specs' => ['پردازنده' => ['مدل' => 'Intel Core i7-1355U']],
            ],
            [
                'name' => 'لپ‌تاپ ۱۵.۶ اینچی دل مدل Inspiron 3520', 'name_en' => 'Dell Inspiron 3520',
                'brand' => 'dell', 'price' => 38_600_000, 'discount' => 15,
                'subtitle' => 'نمایشگر ۱۲۰ هرتز و پردازنده Core i7',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['رم ۱۶ گیگابایت', 'SSD یک ترابایت', 'درگاه HDMI 1.4'],
                'colors' => ['مشکی کربنی' => '#2B2D31'],
                'specs' => ['حافظه' => ['رم' => '۱۶ گیگابایت', 'SSD' => '۱ ترابایت']],
            ],
            [
                'name' => 'لپ‌تاپ ۱۶ اینچی ایسوس مدل ROG Strix G16', 'name_en' => 'Asus ROG Strix G16',
                'brand' => 'asus', 'price' => 89_200_000, 'discount' => 12,
                'subtitle' => 'لپ‌تاپ گیمینگ با RTX 4060',
                'warranty' => '۲۴ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر ۱۶۵ هرتز', 'کیبورد RGB چهارناحیه', 'خنک‌کننده فلز مایع'],
                'colors' => ['خاکستری اکلیپس' => '#3A3D42'],
                'specs' => ['گرافیک' => ['مدل' => 'RTX 4060 8GB']],
            ],
        ],

        'tablet' => [
            [
                'name' => 'تبلت اپل مدل iPad Air 5 نسخه ۱۰.۹ اینچی', 'name_en' => 'Apple iPad Air 5',
                'brand' => 'apple', 'price' => 42_800_000, 'discount' => 7,
                'subtitle' => 'تراشه M1 و پشتیبانی از Apple Pencil 2',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر Liquid Retina', 'تراشه M1', 'دوربین سلفی Center Stage'],
                'colors' => ['آبی' => '#7C99B4', 'بنفش' => '#B0A5C7', 'خاکستری' => '#8B8D90'],
                'options' => ['name' => 'حافظه داخلی', 'values' => ['۶۴ گیگابایت' => 0, '۲۵۶ گیگابایت' => 65_000_000]],
                'specs' => ['نمایشگر' => ['اندازه' => '۱۰.۹ اینچ']],
            ],
            [
                'name' => 'تبلت سامسونگ مدل Galaxy Tab S9 FE', 'name_en' => 'Samsung Galaxy Tab S9 FE',
                'brand' => 'samsung', 'price' => 29_800_000, 'discount' => 14,
                'subtitle' => 'همراه با قلم S Pen و مقاومت IP68',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر ۱۰.۹ اینچی ۹۰ هرتز', 'قلم S Pen داخل جعبه', 'باتری ۸۰۰۰ میلی‌آمپرساعت'],
                'colors' => ['خاکستری' => '#6D7175', 'نعنایی' => '#9FC3B0'],
                'specs' => ['نمایشگر' => ['اندازه' => '۱۰.۹ اینچ', 'نرخ نوسازی' => '۹۰ هرتز']],
            ],
            [
                'name' => 'تبلت شیائومی مدل Pad 6', 'name_en' => 'Xiaomi Pad 6',
                'brand' => 'xiaomi', 'price' => 21_800_000, 'discount' => 16,
                'subtitle' => 'نمایشگر ۱۴۴ هرتز با رزولوشن 2.8K',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['چهار بلندگوی استریو', 'شارژ سریع ۳۳ وات', 'بدنه فلزی یکپارچه'],
                'colors' => ['طلایی شامپاینی' => '#D8C3A5', 'خاکستری' => '#66696D'],
                'specs' => ['نمایشگر' => ['رزولوشن' => '۲۸۸۰ × ۱۸۰۰']],
            ],
        ],

        'smart-watch' => [
            [
                'name' => 'ساعت هوشمند اپل مدل Watch Series 9 سایز ۴۵ میلی‌متری', 'name_en' => 'Apple Watch Series 9',
                'brand' => 'apple', 'price' => 31_200_000, 'discount' => 9,
                'subtitle' => 'تراشه S9 و نمایشگر ۲۰۰۰ نیت',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['حرکت Double Tap', 'سنسور اکسیژن خون', 'مقاومت WR50'],
                'colors' => ['میدنایت' => '#2C333B', 'استارلایت' => '#EDE3D6', 'قرمز' => '#B4322F'],
                'specs' => ['نمایشگر' => ['نوع' => 'Retina LTPO OLED']],
            ],
            [
                'name' => 'ساعت هوشمند سامسونگ مدل Galaxy Watch6 Classic', 'name_en' => 'Samsung Galaxy Watch6 Classic',
                'brand' => 'samsung', 'price' => 24_600_000, 'discount' => 15,
                'subtitle' => 'بازل چرخان فیزیکی و بدنه فولادی',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['پایش خواب پیشرفته', 'تحلیل ترکیب بدن', 'بدنه استیل ضدزنگ'],
                'colors' => ['مشکی' => '#26282C', 'نقره‌ای' => '#C3C6CA'],
                'specs' => ['بدنه' => ['جنس' => 'فولاد ضدزنگ']],
            ],
            [
                'name' => 'مچ‌بند هوشمند شیائومی مدل Smart Band 8', 'name_en' => 'Xiaomi Smart Band 8',
                'brand' => 'xiaomi', 'price' => 2_600_000, 'discount' => 24,
                'subtitle' => 'باتری تا ۱۶ روز و بیش از ۱۵۰ حالت ورزشی',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['نمایشگر AMOLED', 'مقاومت ۵ اتمسفر', 'وزن تنها ۲۷ گرم'],
                'colors' => ['مشکی' => '#212226', 'طلایی' => '#D6BE93'],
                'specs' => ['باتری' => ['شارژدهی' => 'تا ۱۶ روز']],
            ],
        ],

        'headphone' => [
            [
                'name' => 'هدفون بی‌سیم سونی مدل WH-1000XM5', 'name_en' => 'Sony WH-1000XM5',
                'brand' => 'sony', 'price' => 21_800_000, 'discount' => 18,
                'subtitle' => 'حذف نویز فعال در کلاس جهانی',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['۸ میکروفون حذف نویز', 'باتری ۳۰ ساعته', 'شارژ سریع ۳ دقیقه‌ای'],
                'colors' => ['مشکی' => '#1F2023', 'نقره‌ای' => '#D9D6CF'],
                'specs' => ['صدا' => ['درایور' => '۳۰ میلی‌متری', 'کدک' => 'LDAC / AAC / SBC']],
            ],
            [
                'name' => 'هندزفری بی‌سیم اپل مدل AirPods Pro 2', 'name_en' => 'Apple AirPods Pro 2',
                'brand' => 'apple', 'price' => 16_800_000, 'discount' => 11,
                'subtitle' => 'تراشه H2 و کیس شارژ USB-C',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['حذف نویز دو برابر بهتر', 'حالت شفافیت تطبیقی', 'صدای فضایی شخصی‌سازی‌شده'],
                'colors' => ['سفید' => '#F5F5F7'],
                'specs' => ['باتری' => ['شارژدهی' => 'تا ۶ ساعت']],
            ],
            [
                'name' => 'هدفون بی‌سیم جی‌بی‌ال مدل Tune 770NC', 'name_en' => 'JBL Tune 770NC',
                'brand' => 'jbl', 'price' => 6_200_000, 'discount' => 22,
                'subtitle' => 'باتری ۷۰ ساعته با حذف نویز فعال',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['صدای JBL Pure Bass', 'اتصال چنددستگاهی', 'تاشو و سبک'],
                'colors' => ['مشکی' => '#1E1F22', 'آبی' => '#2F4F7A', 'سفید' => '#EDEDEF'],
                'specs' => ['باتری' => ['شارژدهی' => 'تا ۷۰ ساعت']],
            ],
            [
                'name' => 'هندزفری بی‌سیم انکر مدل Soundcore Life P3', 'name_en' => 'Anker Soundcore Life P3',
                'brand' => 'anker', 'price' => 3_800_000, 'discount' => 26,
                'subtitle' => 'حذف نویز چندحالته با اپلیکیشن اختصاصی',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['۶ میکروفون تماس', 'شارژ بی‌سیم', 'حالت بازی با تأخیر ۸۸ میلی‌ثانیه'],
                'colors' => ['مشکی' => '#232427', 'صورتی' => '#E6BCC4', 'آبی' => '#39557F'],
                'specs' => ['اتصال' => ['نسخه بلوتوث' => '۵.۲']],
            ],
        ],

        'camera' => [
            [
                'name' => 'دوربین بدون آینه کنون مدل EOS R50 با لنز 18-45', 'name_en' => 'Canon EOS R50',
                'brand' => 'canon', 'price' => 42_800_000, 'discount' => 8,
                'subtitle' => 'سنسور ۲۴.۲ مگاپیکسلی APS-C',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['فیلم‌برداری 4K/30p', 'فوکوس Dual Pixel CMOS AF II', 'وزن تنها ۳۷۵ گرم'],
                'colors' => ['مشکی' => '#1C1D20', 'سفید' => '#E9E9EB'],
                'specs' => ['سنسور' => ['نوع' => 'CMOS APS-C', 'رزولوشن' => '۲۴.۲ مگاپیکسل']],
            ],
            [
                'name' => 'دوربین بدون آینه نیکون مدل Z50 با لنز 16-50', 'name_en' => 'Nikon Z50',
                'brand' => 'nikon', 'price' => 51_200_000, 'discount' => 10,
                'subtitle' => 'بدنه مقاوم و منوی فارسی‌پسند',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['۲۰.۹ مگاپیکسل', 'نمایشگر تاشو لمسی', 'عکاسی پیوسته ۱۱ فریم'],
                'colors' => ['مشکی' => '#202124'],
                'specs' => ['سنسور' => ['رزولوشن' => '۲۰.۹ مگاپیکسل']],
            ],
        ],

        'gaming' => [
            [
                'name' => 'کنسول بازی سونی مدل PlayStation 5 Slim', 'name_en' => 'Sony PlayStation 5 Slim',
                'brand' => 'sony', 'price' => 59_800_000, 'discount' => 6,
                'subtitle' => 'نسخه دیسک‌خور با حافظه ۱ ترابایت',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['SSD فوق سریع', 'دسته DualSense', 'پشتیبانی از 4K 120Hz'],
                'colors' => ['سفید' => '#F2F3F5'],
                'specs' => ['حافظه' => ['SSD' => '۱ ترابایت']],
            ],
            [
                'name' => 'دسته بازی سونی مدل DualSense', 'name_en' => 'Sony DualSense Controller',
                'brand' => 'sony', 'price' => 6_800_000, 'discount' => 12,
                'subtitle' => 'بازخورد لمسی و ماشه‌های تطبیقی',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['هپتیک پیشرفته', 'میکروفون داخلی', 'باتری قابل شارژ'],
                'colors' => ['سفید' => '#F1F2F4', 'مشکی' => '#232427', 'قرمز' => '#8E2C2C'],
                'specs' => ['اتصال' => ['نوع' => 'بلوتوث و USB-C']],
            ],
        ],

        'mobile-accessory' => [
            [
                'name' => 'پاوربانک انکر مدل PowerCore 20000mAh', 'name_en' => 'Anker PowerCore 20000',
                'brand' => 'anker', 'price' => 3_200_000, 'discount' => 20,
                'subtitle' => 'شارژ سریع ۳۰ وات با پورت USB-C',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['ظرفیت واقعی بالا', 'محافظت چندلایه', 'قابلیت شارژ لپ‌تاپ سبک'],
                'colors' => ['مشکی' => '#212327'],
                'specs' => ['باتری' => ['ظرفیت' => '۲۰۰۰۰ میلی‌آمپرساعت']],
            ],
            [
                'name' => 'شارژر دیواری انکر مدل Nano II 65W', 'name_en' => 'Anker Nano II 65W',
                'brand' => 'anker', 'price' => 1_850_000, 'discount' => 15,
                'subtitle' => 'فناوری گالیوم نیترید و ابعاد بسیار کوچک',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['GaN II', 'شارژ سریع لپ‌تاپ', 'حجم ۵۸٪ کمتر'],
                'colors' => ['مشکی' => '#25262A', 'سفید' => '#F0F1F3'],
                'specs' => ['توان' => ['خروجی' => '۶۵ وات']],
            ],
            [
                'name' => 'کابل شارژ USB-C به لایتنینگ انکر طول ۱.۸ متر', 'name_en' => 'Anker USB-C to Lightning',
                'brand' => 'anker', 'price' => 980_000, 'discount' => 18,
                'subtitle' => 'دارای استاندارد MFi اپل',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['مقاوم در برابر ۱۲۰۰۰ بار خم شدن', 'انتقال توان ۳۰ وات'],
                'colors' => ['سفید' => '#F2F2F4', 'مشکی' => '#212226'],
                'specs' => ['کابل' => ['طول' => '۱.۸ متر']],
            ],
        ],

        'refrigerator' => [
            [
                'name' => 'یخچال فریزر ساید بای ساید ال‌جی مدل X257', 'name_en' => 'LG Side by Side X257',
                'brand' => 'lg', 'price' => 189_000_000, 'discount' => 12,
                'subtitle' => 'ظرفیت ۲۶ فوت با یخساز اتوماتیک',
                'warranty' => '۶۰ ماه گارانتی کمپرسور',
                'highlights' => ['کمپرسور اینورتر خطی', 'فیلتر ضدباکتری', 'نمایشگر لمسی درب'],
                'colors' => ['نقره‌ای' => '#B8BCC0', 'مشکی' => '#26282B'],
                'specs' => ['ابعاد' => ['ظرفیت' => '۲۶ فوت'], 'مصرف انرژی' => ['رتبه' => 'A++']],
            ],
            [
                'name' => 'یخچال فریزر دوقلو اسنوا مدل S8-2352', 'name_en' => 'Snowa Twin S8',
                'brand' => 'snowa', 'price' => 129_000_000, 'discount' => 16,
                'subtitle' => 'ظرفیت ۴۰ فوت با سیستم نوفراست',
                'warranty' => '۱۸ ماه گارانتی و ۱۰ سال خدمات',
                'highlights' => ['سیستم بدون برفک', 'روشنایی LED داخلی', 'کشوی میوه رطوبت‌دار'],
                'colors' => ['سفید' => '#F0F1F2', 'استیل' => '#AFB4B9'],
                'specs' => ['ابعاد' => ['ظرفیت' => '۴۰ فوت']],
            ],
        ],

        'washing-machine' => [
            [
                'name' => 'ماشین لباسشویی بوش مدل WGA254X0ME ظرفیت ۱۰ کیلوگرم', 'name_en' => 'Bosch WGA254X0ME',
                'brand' => 'bosch', 'price' => 112_000_000, 'discount' => 14,
                'subtitle' => 'موتور EcoSilence Drive با ضمانت ۱۰ ساله',
                'warranty' => '۲۴ ماه گارانتی شرکتی',
                'highlights' => ['۱۴۰۰ دور در دقیقه', 'برنامه ضدآلرژی', 'مصرف انرژی A'],
                'colors' => ['سفید' => '#EFF0F1'],
                'specs' => ['ظرفیت' => ['وزن' => '۱۰ کیلوگرم'], 'موتور' => ['نوع' => 'EcoSilence Drive']],
            ],
            [
                'name' => 'ماشین لباسشویی پاکشوما مدل TFB-96402 ظرفیت ۹ کیلوگرم', 'name_en' => 'Pakshoma TFB-96402',
                'brand' => 'pakshoma', 'price' => 48_600_000, 'discount' => 19,
                'subtitle' => 'دارای موتور کم‌مصرف و درب کریستالی',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['۱۶ برنامه شست‌وشو', 'قفل کودک', 'نمایشگر LED'],
                'colors' => ['سفید' => '#F1F1F3', 'نقره‌ای' => '#C4C7CA'],
                'specs' => ['ظرفیت' => ['وزن' => '۹ کیلوگرم']],
            ],
        ],

        'vacuum' => [
            [
                'name' => 'جاروبرقی بوش مدل BGLS4X200 توان ۲۰۰۰ وات', 'name_en' => 'Bosch BGLS4X200',
                'brand' => 'bosch', 'price' => 26_800_000, 'discount' => 17,
                'subtitle' => 'فیلتر HEPA و کیسه ۴ لیتری',
                'warranty' => '۲۴ ماه گارانتی شرکتی',
                'highlights' => ['فیلتر HEPA H13', 'صدای کم', 'کابل ۹ متری'],
                'colors' => ['قرمز' => '#9E2B2B', 'مشکی' => '#26272A'],
                'specs' => ['توان' => ['وات' => '۲۰۰۰ وات']],
            ],
            [
                'name' => 'جاروشارژی شیائومی مدل Mi Vacuum Cleaner G11', 'name_en' => 'Xiaomi Vacuum G11',
                'brand' => 'xiaomi', 'price' => 19_800_000, 'discount' => 21,
                'subtitle' => 'مکش ۱۲۰ ایرووات با باتری قابل تعویض',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['۶۰ دقیقه کارکرد', 'نمایشگر لمسی', 'فیلتراسیون ۵ لایه'],
                'colors' => ['سفید' => '#F0F1F3'],
                'specs' => ['باتری' => ['کارکرد' => 'تا ۶۰ دقیقه']],
            ],
        ],

        'kitchen-appliance' => [
            [
                'name' => 'سرخ‌کن بدون روغن فیلیپس مدل Airfryer XL', 'name_en' => 'Philips Airfryer XL',
                'brand' => 'bosch', 'price' => 18_900_000, 'discount' => 20,
                'subtitle' => 'ظرفیت ۶.۲ لیتر مناسب خانواده',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['۹۰٪ چربی کمتر', 'صفحه لمسی', 'قابل شست‌وشو در ماشین ظرفشویی'],
                'colors' => ['مشکی' => '#212226'],
                'specs' => ['ظرفیت' => ['حجم' => '۶.۲ لیتر']],
            ],
            [
                'name' => 'مایکروویو ال‌جی مدل MS2535GIS ظرفیت ۲۵ لیتر', 'name_en' => 'LG MS2535GIS',
                'brand' => 'lg', 'price' => 24_600_000, 'discount' => 13,
                'subtitle' => 'پوشش داخلی EasyClean و گرمایش یکنواخت',
                'warranty' => '۲۴ ماه گارانتی شرکتی',
                'highlights' => ['موج یکنواخت', 'صفحه لمسی', 'قفل کودک'],
                'colors' => ['استیل' => '#B4B8BC'],
                'specs' => ['ظرفیت' => ['حجم' => '۲۵ لیتر']],
            ],
        ],

        'sneakers' => [
            [
                'name' => 'کفش مخصوص دویدن مردانه نایک مدل Revolution 7', 'name_en' => 'Nike Revolution 7',
                'brand' => 'nike', 'price' => 4_200_000, 'discount' => 25,
                'subtitle' => 'رویه مش تنفس‌پذیر با میانه فوم نرم',
                'warranty' => 'ضمانت اصالت کالا',
                'highlights' => ['وزن سبک', 'کفی قابل جداشدن', 'زیره لاستیکی ضدسایش'],
                'colors' => ['مشکی' => '#1F2023', 'سفید' => '#F0F0F2', 'سرمه‌ای' => '#2C3A55'],
                'options' => ['name' => 'سایز', 'values' => ['۴۰' => 0, '۴۱' => 0, '۴۲' => 0, '۴۳' => 0, '۴۴' => 0]],
                'specs' => ['مشخصات' => ['جنس رویه' => 'مش', 'جنس زیره' => 'لاستیک']],
            ],
            [
                'name' => 'کفش ورزشی زنانه آدیداس مدل Duramo SL', 'name_en' => 'Adidas Duramo SL',
                'brand' => 'adidas', 'price' => 3_850_000, 'discount' => 22,
                'subtitle' => 'مناسب پیاده‌روی و تمرین‌های سبک',
                'warranty' => 'ضمانت اصالت کالا',
                'highlights' => ['میانه Lightmotion', 'رویه بازیافتی', 'پاشنه پدداردار'],
                'colors' => ['سفید' => '#F2F2F4', 'صورتی' => '#E5B7C1', 'مشکی' => '#232427'],
                'options' => ['name' => 'سایز', 'values' => ['۳۷' => 0, '۳۸' => 0, '۳۹' => 0, '۴۰' => 0]],
                'specs' => ['مشخصات' => ['کاربرد' => 'پیاده‌روی و دویدن سبک']],
            ],
            [
                'name' => 'کفش راحتی مردانه پوما مدل Anzarun Lite', 'name_en' => 'Puma Anzarun Lite',
                'brand' => 'puma', 'price' => 3_400_000, 'discount' => 28,
                'subtitle' => 'طراحی رترو با راحتی روزمره',
                'warranty' => 'ضمانت اصالت کالا',
                'highlights' => ['زیره SoftFoam+', 'رویه مش و چرم مصنوعی'],
                'colors' => ['طوسی' => '#8A8D91', 'مشکی' => '#212226'],
                'options' => ['name' => 'سایز', 'values' => ['۴۰' => 0, '۴۲' => 0, '۴۴' => 0]],
                'specs' => ['مشخصات' => ['فصل' => 'چهارفصل']],
            ],
        ],

        'tshirt' => [
            [
                'name' => 'تی‌شرت آستین کوتاه مردانه نایک مدل Sportswear Club', 'name_en' => 'Nike Sportswear Club Tee',
                'brand' => 'nike', 'price' => 1_480_000, 'discount' => 30,
                'subtitle' => 'پنبه ۱۰۰٪ با دوخت تقویت‌شده',
                'warranty' => 'ضمانت اصالت کالا',
                'highlights' => ['جنس نخ پنبه', 'یقه ریب‌بافت', 'تناسب استاندارد'],
                'colors' => ['مشکی' => '#202124', 'سفید' => '#F3F3F5', 'زیتونی' => '#4E5A44'],
                'options' => ['name' => 'سایز', 'values' => ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0]],
                'specs' => ['مشخصات' => ['جنس' => 'نخ پنبه', 'فرم' => 'استاندارد']],
            ],
            [
                'name' => 'پولوشرت مردانه آدیداس مدل Essentials', 'name_en' => 'Adidas Essentials Polo',
                'brand' => 'adidas', 'price' => 1_950_000, 'discount' => 24,
                'subtitle' => 'پارچه پیکه خنک و جذب رطوبت',
                'warranty' => 'ضمانت اصالت کالا',
                'highlights' => ['فناوری AEROREADY', 'یقه دکمه‌دار'],
                'colors' => ['سرمه‌ای' => '#2A3A55', 'سفید' => '#F0F1F3'],
                'options' => ['name' => 'سایز', 'values' => ['M' => 0, 'L' => 0, 'XL' => 0]],
                'specs' => ['مشخصات' => ['جنس' => 'پیکه پلی‌استر']],
            ],
        ],

        'bag' => [
            [
                'name' => 'کوله‌پشتی لپ‌تاپ ۱۵.۶ اینچی درسا مدل Urban', 'name_en' => 'Dorsa Urban Backpack',
                'brand' => 'dorsa', 'price' => 2_450_000, 'discount' => 20,
                'subtitle' => 'ضدآب با درگاه شارژ USB',
                'warranty' => '۱۲ ماه ضمانت دوخت',
                'highlights' => ['محفظه ضدضربه لپ‌تاپ', 'جیب مخفی پشتی', 'بندهای ارگونومیک'],
                'colors' => ['مشکی' => '#212327', 'خاکستری' => '#6D7175'],
                'specs' => ['مشخصات' => ['ظرفیت' => '۲۵ لیتر']],
            ],
            [
                'name' => 'کیف دوشی چرم طبیعی درسا مدل کلاسیک', 'name_en' => 'Dorsa Leather Shoulder Bag',
                'brand' => 'dorsa', 'price' => 5_800_000, 'discount' => 15,
                'subtitle' => 'چرم گاوی دست‌دوز',
                'warranty' => '۲۴ ماه ضمانت چرم',
                'highlights' => ['چرم طبیعی', 'بند قابل تنظیم', 'آستر پارچه‌ای مقاوم'],
                'colors' => ['قهوه‌ای' => '#6B4A32', 'مشکی' => '#232426'],
                'specs' => ['مشخصات' => ['جنس' => 'چرم طبیعی']],
            ],
        ],

        'wrist-watch' => [
            [
                'name' => 'ساعت مچی عقربه‌ای مردانه کاسیو مدل MTP-V002', 'name_en' => 'Casio MTP-V002',
                'brand' => 'casio', 'price' => 1_650_000, 'discount' => 18,
                'subtitle' => 'کلاسیک، سبک و مقاوم در برابر آب',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['مقاومت ۳۰ متری', 'بند استیل', 'شیشه معدنی'],
                'colors' => ['نقره‌ای' => '#C2C6CA', 'طلایی' => '#CBA35B'],
                'specs' => ['مشخصات' => ['جنس بند' => 'استیل']],
            ],
        ],

        'perfume' => [
            [
                'name' => 'ادوپرفیوم مردانه سین‌رجیس مدل Noir حجم ۱۰۰ میلی‌لیتر', 'name_en' => 'Sinregis Noir EDP',
                'brand' => 'sinregis', 'price' => 2_800_000, 'discount' => 26,
                'subtitle' => 'رایحه چوبی-تند با ماندگاری بالا',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['ماندگاری ۸ تا ۱۰ ساعت', 'مناسب فصل سرد', 'پخش بوی متوسط رو به بالا'],
                'colors' => [],
                'specs' => ['مشخصات' => ['حجم' => '۱۰۰ میلی‌لیتر', 'گروه بویایی' => 'چوبی تند']],
            ],
            [
                'name' => 'ادوتویلت زنانه لورآل مدل Blossom حجم ۷۵ میلی‌لیتر', 'name_en' => "L'Oreal Blossom EDT",
                'brand' => 'loreal', 'price' => 2_150_000, 'discount' => 19,
                'subtitle' => 'رایحه گلی-میوه‌ای بهاری',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['مناسب استفاده روزانه', 'رایحه ملایم'],
                'colors' => [],
                'specs' => ['مشخصات' => ['حجم' => '۷۵ میلی‌لیتر']],
            ],
        ],

        'skin-care' => [
            [
                'name' => 'کرم مرطوب‌کننده نیوآ مدل Soft حجم ۲۰۰ میلی‌لیتر', 'name_en' => 'Nivea Soft Cream',
                'brand' => 'nivea', 'price' => 490_000, 'discount' => 15,
                'subtitle' => 'حاوی جوجوبا و ویتامین E',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['جذب سریع', 'مناسب صورت، دست و بدن'],
                'colors' => [],
                'specs' => ['مشخصات' => ['حجم' => '۲۰۰ میلی‌لیتر']],
            ],
            [
                'name' => 'ضدآفتاب بی‌رنگ SPF50 لورآل حجم ۵۰ میلی‌لیتر', 'name_en' => "L'Oreal Sunscreen SPF50",
                'brand' => 'loreal', 'price' => 980_000, 'discount' => 22,
                'subtitle' => 'محافظت گسترده در برابر UVA و UVB',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['بدون چربی', 'مناسب پوست حساس'],
                'colors' => [],
                'specs' => ['مشخصات' => ['SPF' => '۵۰']],
            ],
        ],

        'makeup' => [
            [
                'name' => 'رژ لب مایع مات لورآل مدل Infallible', 'name_en' => "L'Oreal Infallible Matte",
                'brand' => 'loreal', 'price' => 720_000, 'discount' => 18,
                'subtitle' => 'ماندگاری تا ۱۶ ساعت',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['فرمول سبک', 'بدون خشکی لب'],
                'colors' => ['قرمز کلاسیک' => '#B02A38', 'نود' => '#C58C79', 'صورتی' => '#D96C86'],
                'specs' => ['مشخصات' => ['نوع' => 'مایع مات']],
            ],
        ],

        'book' => [
            [
                'name' => 'کتاب ملت عشق اثر الیف شافاک نشر ققنوس', 'name_en' => 'Melat-e Eshgh',
                'brand' => 'cheshmeh', 'price' => 390_000, 'discount' => 12,
                'subtitle' => 'ترجمه ارسلان فصیحی — چاپ جدید',
                'warranty' => 'ضمانت سلامت فیزیکی کالا',
                'highlights' => ['۵۱۲ صفحه', 'جلد شومیز', 'کاغذ بالکی'],
                'colors' => [],
                'specs' => ['مشخصات' => ['تعداد صفحه' => '۵۱۲', 'قطع' => 'رقعی']],
            ],
            [
                'name' => 'کتاب اثر مرکب اثر دارن هاردی', 'name_en' => 'The Compound Effect',
                'brand' => 'cheshmeh', 'price' => 240_000, 'discount' => 10,
                'subtitle' => 'راهنمای عملی رشد فردی',
                'warranty' => 'ضمانت سلامت فیزیکی کالا',
                'highlights' => ['۲۲۴ صفحه', 'ترجمه روان'],
                'colors' => [],
                'specs' => ['مشخصات' => ['تعداد صفحه' => '۲۲۴']],
            ],
        ],

        'stationery' => [
            [
                'name' => 'دفتر یادداشت سیمی ۱۰۰ برگ کنکو', 'name_en' => 'Kenko Notebook 100p',
                'brand' => 'kenko', 'price' => 125_000, 'discount' => 14,
                'subtitle' => 'کاغذ ۸۰ گرمی با جلد سخت',
                'warranty' => 'ضمانت سلامت فیزیکی کالا',
                'highlights' => ['صحافی سیمی', 'کاغذ بدون خط‌افتادگی'],
                'colors' => ['آبی' => '#33608F', 'مشکی' => '#25262A'],
                'specs' => ['مشخصات' => ['تعداد برگ' => '۱۰۰ برگ']],
            ],
        ],

        'sport-gear' => [
            [
                'name' => 'مت یوگا ضدلغزش ۶ میلی‌متری کنکو', 'name_en' => 'Kenko Yoga Mat 6mm',
                'brand' => 'kenko', 'price' => 680_000, 'discount' => 20,
                'subtitle' => 'جنس TPE دولایه با بند حمل',
                'warranty' => '۶ ماه ضمانت کالا',
                'highlights' => ['ضدلغزش', 'قابل شست‌وشو', 'سبک و قابل حمل'],
                'colors' => ['بنفش' => '#7A64A0', 'آبی' => '#3E7FA8'],
                'specs' => ['مشخصات' => ['ضخامت' => '۶ میلی‌متر']],
            ],
            [
                'name' => 'دمبل روکش‌دار ۵ کیلوگرمی (جفت)', 'name_en' => 'Coated Dumbbell 5kg',
                'brand' => 'kenko', 'price' => 890_000, 'discount' => 12,
                'subtitle' => 'روکش نئوپرن ضدلغزش',
                'warranty' => '۱۲ ماه ضمانت کالا',
                'highlights' => ['مناسب تمرین خانگی', 'روکش محافظ کف'],
                'colors' => ['مشکی' => '#232427'],
                'specs' => ['مشخصات' => ['وزن' => '۵ کیلوگرم (هر عدد)']],
            ],
        ],

        'luggage' => [
            [
                'name' => 'چمدان چرخ‌دار سایز بزرگ درسا مدل Voyage', 'name_en' => 'Dorsa Voyage Luggage',
                'brand' => 'dorsa', 'price' => 6_200_000, 'discount' => 23,
                'subtitle' => 'بدنه پلی‌کربنات با قفل TSA',
                'warranty' => '۲۴ ماه ضمانت بدنه',
                'highlights' => ['چهار چرخ ۳۶۰ درجه', 'قفل رمزدار TSA', 'دسته تلسکوپی'],
                'colors' => ['مشکی' => '#212226', 'آبی' => '#2E4D7A', 'نقره‌ای' => '#BFC3C7'],
                'specs' => ['مشخصات' => ['سایز' => 'بزرگ (۲۸ اینچ)']],
            ],
        ],

        'car-accessory' => [
            [
                'name' => 'جاموبایلی مغناطیسی خودرو انکر', 'name_en' => 'Anker Magnetic Car Mount',
                'brand' => 'anker', 'price' => 540_000, 'discount' => 25,
                'subtitle' => 'پایه محکم با آهنربای نئودیمیوم',
                'warranty' => '۱۸ ماه گارانتی شرکتی',
                'highlights' => ['نصب آسان روی دریچه کولر', 'چرخش ۳۶۰ درجه'],
                'colors' => ['مشکی' => '#212226'],
                'specs' => ['مشخصات' => ['نوع نصب' => 'دریچه کولر']],
            ],
            [
                'name' => 'جاروشارژی خودرو رونیکس مدل RH-4291', 'name_en' => 'Ronix RH-4291',
                'brand' => 'ronix', 'price' => 1_280_000, 'discount' => 18,
                'subtitle' => 'مکش قوی با فیلتر قابل شست‌وشو',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['سبک و کم‌حجم', 'دو سری نازل'],
                'colors' => ['قرمز' => '#A32C2C'],
                'specs' => ['مشخصات' => ['ولتاژ' => '۱۲ ولت']],
            ],
        ],

        'tools' => [
            [
                'name' => 'دریل پیچ‌گوشتی شارژی رونیکس مدل 8012', 'name_en' => 'Ronix 8012 Drill',
                'brand' => 'ronix', 'price' => 3_250_000, 'discount' => 16,
                'subtitle' => 'ولتاژ ۱۲ ولت با دو باتری لیتیومی',
                'warranty' => '۲۴ ماه گارانتی شرکتی',
                'highlights' => ['۲ سرعته', 'کیف حمل و متعلقات', 'چراغ LED'],
                'colors' => ['قرمز' => '#A62C2C'],
                'specs' => ['مشخصات' => ['ولتاژ' => '۱۲ ولت']],
            ],
            [
                'name' => 'مجموعه ۱۰۸ عددی ابزار خانگی رونیکس', 'name_en' => 'Ronix 108pcs Tool Set',
                'brand' => 'ronix', 'price' => 2_190_000, 'discount' => 22,
                'subtitle' => 'کیف کامل ابزار برای تعمیرات خانه',
                'warranty' => '۱۲ ماه گارانتی شرکتی',
                'highlights' => ['فولاد کروم وانادیوم', 'جعبه سازمان‌دهی‌شده'],
                'colors' => ['قرمز' => '#A62C2C'],
                'specs' => ['مشخصات' => ['تعداد قطعات' => '۱۰۸ عدد']],
            ],
        ],

        'grocery' => [
            [
                'name' => 'برنج ایرانی هاشمی درجه یک ۱۰ کیلوگرمی', 'name_en' => 'Hashemi Rice 10kg',
                'brand' => 'delster', 'price' => 1_250_000, 'discount' => 8,
                'subtitle' => 'محصول شالیزارهای گیلان',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['بوی طبیعی', 'بسته‌بندی بهداشتی'],
                'colors' => [],
                'specs' => ['مشخصات' => ['وزن' => '۱۰ کیلوگرم']],
            ],
            [
                'name' => 'روغن زیتون فرابکر ۱ لیتری', 'name_en' => 'Extra Virgin Olive Oil 1L',
                'brand' => 'delster', 'price' => 640_000, 'discount' => 12,
                'subtitle' => 'اسیدیته کمتر از ۰.۸ درصد',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['سرد فشرده', 'بطری شیشه‌ای تیره'],
                'colors' => [],
                'specs' => ['مشخصات' => ['حجم' => '۱ لیتر']],
            ],
        ],

        'beverage' => [
            [
                'name' => 'ماءالشعیر دلستر طعم لیمو باکس ۶ عددی', 'name_en' => 'Delster Lemon 6pack',
                'brand' => 'delster', 'price' => 198_000, 'discount' => 10,
                'subtitle' => 'بدون الکل، حاوی ویتامین B',
                'warranty' => 'ضمانت اصالت و سلامت کالا',
                'highlights' => ['بسته ۶ عددی', 'قوطی ۳۳۰ میلی‌لیتری'],
                'colors' => [],
                'specs' => ['مشخصات' => ['حجم هر عدد' => '۳۳۰ میلی‌لیتر']],
            ],
        ],
    ],
];
