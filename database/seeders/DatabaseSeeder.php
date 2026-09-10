<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin & User
        User::create(['name'=>'مدیر دیجینو','email'=>'admin@digino.com','phone'=>'09123456789','password'=>Hash::make('password'),'is_admin'=>true]);
        User::create(['name'=>'علی محمدی','email'=>'ali.mohammadi@gmail.com','phone'=>'09120000000','password'=>Hash::make('password'),'is_admin'=>false]);
        for($i=1;$i<=20;$i++) User::create(['name'=> "کاربر $i", 'email'=> "user{$i}@example.com", 'phone'=> '0912'.rand(1000000,9999999), 'password'=>Hash::make('password')]);

        // Brands
        $brands = [
            ['name'=>'Apple','slug'=>'apple'], ['name'=>'Samsung','slug'=>'samsung'], ['name'=>'Xiaomi','slug'=>'xiaomi'],
            ['name'=>'Sony','slug'=>'sony'], ['name'=>'Asus','slug'=>'asus'], ['name'=>'HP','slug'=>'hp'],
            ['name'=>'Lenovo','slug'=>'lenovo'], ['name'=>'Microsoft','slug'=>'microsoft'], ['name'=>'JBL','slug'=>'jbl'], ['name'=>'Canon','slug'=>'canon']
        ];
        foreach($brands as $b) Brand::create($b);

        // Categories
        $cats = [
            ['name'=>'موبایل','slug'=>'mobile','icon'=>'phone'],
            ['name'=>'لپتاپ','slug'=>'laptop','icon'=>'laptop'],
            ['name'=>'تبلت','slug'=>'tablet','icon'=>'tablet'],
            ['name'=>'ساعت هوشمند','slug'=>'smartwatch','icon'=>'watch'],
            ['name'=>'هدفون و اسپیکر','slug'=>'audio','icon'=>'headphones'],
            ['name'=>'کنسول بازی','slug'=>'gaming','icon'=>'gamepad'],
            ['name'=>'لوازم جانبی','slug'=>'accessories','icon'=>'usb'],
            ['name'=>'دوربین','slug'=>'camera','icon'=>'camera'],
            ['name'=>'مانیتور','slug'=>'monitor','icon'=>'monitor'],
            ['name'=>'کامپیوتر و لپتاپ','slug'=>'computer','icon'=>'computer'],
        ];
        foreach($cats as $c) Category::create($c);

        // Products - 60 fake products
        $productData = [
            ['Galaxy S24 Ultra',56900000,12,1,'موبایل پرچمدار سامسونگ'], ['iPhone 15 Pro Max',72900000,89,1,'آیفون اپل'], ['MacBook Air M2 2023',46500000,74,2,'لپتاپ اپل'], ['Redmi Watch 4',4790000,0,4,'ساعت هوشمند'], ['WH-1000XM5',15900000,9,5,'هدفون سونی'], ['PlayStation 5',29900000,6,6,'کنسول بازی'],
            ['AirPods Pro 2',11490000,10,5,'ایربادز اپل'], ['Galaxy Tab S9 FE',18900000,0,3,'تبلت سامسونگ'], ['PlayStation 5 Slim',29900000,6,6,'کنسول سونی'], ['Watch Series 9',23500000,0,4,'ساعت اپل'], ['VivoBook 15 X1504',15500000,8,2,'لپتاپ ایسوس'], ['Soundcore Life Q35',6200000,0,5,'هدفون انکر'],
            ['JBL Flip 6',7650000,11,5,'اسپیکر جی بی ال'], ['Redmi Note 13 Pro',15900000,11,1,'شیائومی'], ['EOS 2000D',23800000,0,8,'دوربین کنون'], ['Mi Band 8',2190000,6,4,'مچ بند شیائومی'],
            ['PowerPort III 65W',2490000,0,7,'شارژر انکر'], ['24MR400-B',7900000,0,9,'مانیتور ال جی'], ['HDD Toshiba 1TB',3150000,5,10,'هارد اکسترنال'], ['MX Master 3S',4950000,0,7,'موس لاجیتک'],
            ['K552',2890000,0,10,'کیبورد ردراگون'], ['DUAL RTX 4060',18900000,7,10,'کارت گرافیک ایسوس'], ['Galaxy S23 Ultra',47500000,0,1,'سامسونگ'], ['Xiaom i4 Ultra',42200000,0,1,'شیائومی'],
            ['Galaxy Watch 6',13900000,0,4,'ساعت هوشمند'], ['JBL Charge 5',8800000,0,5,'اسپیکر'], ['iPhone 15',72000000,0,1,'آیفون'], ['A55',23500000,0,1,'سامسونگ'],
            ['Redmi Note 13 Pro+',15900000,0,1,'شیائومی'], ['Apple Watch Series 9',9900000,0,4,'اپل'], ['Lenovo Ideapad 15',21900000,0,2,'لنوو'], ['iPad Air',31000000,0,3,'اپل'],
            ['AirPods 3',8900000,0,5,'اپل'], ['Galaxy Buds 2 Pro',6500000,15,5,'سامسونگ'], ['MacBook Pro 14',89000000,5,2,'اپل'], ['Surface Laptop',54000000,0,2,'مایکروسافت'],
            ['Sony WH-1000XM4',13500000,12,5,'سونی'], ['Garmin Forerunner',8900000,0,4,'گارمین'], ['PS5 Controller',3200000,0,6,'دسته بازی'], ['Xbox Series S',18900000,0,6,'ایکس باکس'],
            ['Canon EOS R5',89000000,0,8,'کنون'], ['Nikon D3500',18900000,0,8,'نیکون'], ['HP Pavilion 15',34500000,0,2,'اچ پی'], ['Asus ROG',67000000,10,2,'ایسوس'],
            ['Samsung Monitor 27',12900000,0,9,'سامسونگ'], ['LG OLED 48',45000000,0,9,'ال جی'], ['iPad Pro 12.9',55000000,0,3,'اپل'], ['Apple Pencil',4800000,0,7,'اپل'],
            ['Anker PowerBank',1890000,0,7,'انکر'], ['Xiaomi Band 7',1200000,0,4,'شیائومی'], ['JBL Headphone',4200000,0,5,'جی بی ال'], ['Sennheiser HD450',5800000,0,5,'سنهایزر'],
            ['Logitech G502',2800000,0,7,'لاجیتک'], ['Razer Keyboard',4500000,0,10,'ریزر'], ['Dell XPS 13',72000000,0,2,'دل'], ['Huawei Watch GT',5900000,0,4,'هواوی'],
        ];
        foreach($productData as $idx=>$p){
            $cat = Category::find($p[3] ?? 1);
            Product::create([
                'name'=>$p[0],
                'slug'=>\Str::slug($p[0]).'-'.($idx+1),
                'sku'=>'SKU-'.str_pad($idx+1,5,'0',STR_PAD_LEFT),
                'description'=>'توضیحات کامل درباره '.$p[0].' - محصولی با کیفیت ساخت بالا، طراحی زیبا و کارایی فوق‌العاده. مناسب برای استفاده روزمره و حرفه‌ای. دارای گارانتی اصالت و سلامت فیزیکی.',
                'short_description'=>$p[4],
                'price'=>$p[1],
                'discount_price'=> $p[2] ? intval($p[1]*(1-$p[2]/100)) : null,
                'discount_percent'=> $p[2] ?: null,
                'stock'=>rand(5,150),
                'category_id'=>$cat?->id ?? 1,
                'brand_id'=>rand(1,10),
                'is_active'=>true,
                'is_featured'=>$idx<12,
                'views'=>rand(34,4200),
                'rating'=>round(rand(35,50)/10,1),
                'reviews_count'=>rand(12,156),
                'specs'=>['weight'=>'250g','battery'=>'5000mAh','display'=>'6.8 AMOLED','ram'=>'12GB'],
                'images'=>["/images/products/product-".(($idx%12)+1).".jpg"]
            ]);
        }

        // Banners
        Banner::create(['title'=>'تخفیف هدفون','image'=>'/images/banners/banner-headphone.jpg','link'=>'/shop?category=audio','position'=>'home','is_active'=>true]);
        Banner::create(['title'=>'ساعت هوشمند','image'=>'/images/banners/banner-watch.jpg','link'=>'/shop?category=smartwatch','position'=>'home','is_active'=>true]);
        Banner::create(['title'=>'hero','image'=>'/images/banners/hero.jpg','link'=>'/shop','position'=>'hero','is_active'=>true]);

        // Coupons
        Coupon::create(['code'=>'DIGINO10','discount_percent'=>10,'min_amount'=>500000,'is_active'=>true]);
        Coupon::create(['code'=>'WELCOME20','discount_percent'=>20,'min_amount'=>1000000,'is_active'=>true]);

        // Orders sample
        $users = User::where('is_admin',false)->take(5)->get();
        foreach($users as $u){
            $order = Order::create(['user_id'=>$u->id,'total'=>rand(5000000,80000000),'status'=>collect(['pending','processing','shipped','delivered','cancelled'])->random(),'address'=>'تهران، خیابان انقلاب، پلاک 123','payment_method'=>'online','tracking_code'=>'TRK'.rand(100000,999999)]);
            $prods = Product::inRandomOrder()->take(rand(1,3))->get();
            foreach($prods as $prod) OrderItem::create(['order_id'=>$order->id,'product_id'=>$prod->id,'quantity'=>rand(1,2),'price'=>$prod->final_price]);
        }
    }
}
