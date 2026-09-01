<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Address;
use App\Models\User;
use App\Support\Iran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public const FIRST_NAMES = [
        'علی', 'محمد', 'رضا', 'حسین', 'امیر', 'مهدی', 'سعید', 'نیما', 'کاوه', 'بهزاد',
        'سارا', 'زهرا', 'مریم', 'نگین', 'الهام', 'شیما', 'پریسا', 'نازنین', 'مینا', 'هدیه',
        'یاسمن', 'آرش', 'سپهر', 'فرزاد', 'کیانا', 'رها', 'سمانه', 'بهار', 'آیدا', 'پویا',
    ];

    public const LAST_NAMES = [
        'محمدی', 'حسینی', 'رضایی', 'کریمی', 'موسوی', 'احمدی', 'صادقی', 'جعفری', 'قاسمی', 'نوروزی',
        'شریفی', 'اکبری', 'مرادی', 'زارعی', 'یوسفی', 'عباسی', 'طاهری', 'کاظمی', 'سلطانی', 'فتحی',
    ];

    public function run(): void
    {
        // ------------------------------------------------------------ staff
        $admin = User::create([
            'name' => 'یارمحمدی',
            'email' => 'admin@digino.test',
            'mobile' => '09120000001',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin->value,
            'is_active' => true,
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
            'newsletter' => true,
        ]);

        User::create([
            'name' => 'نگین شریفی',
            'email' => 'manager@digino.test',
            'mobile' => '09120000002',
            'password' => Hash::make('password'),
            'role' => UserRole::Manager->value,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ------------------------------------------------------- demo buyer
        $demo = User::create([
            'name' => 'سارا محمدی',
            'email' => 'user@digino.test',
            'mobile' => '09120000003',
            'password' => Hash::make('password'),
            'role' => UserRole::Customer->value,
            'is_active' => true,
            'email_verified_at' => now(),
            'national_code' => '0064238812',
            'birthday' => '1996-05-14',
            'gender' => 'female',
            'newsletter' => true,
            'loyalty_points' => 1840,
        ]);

        $this->addresses($demo, 2);

        // -------------------------------------------------------- customers
        $used = [];

        for ($i = 0; $i < 120; $i++) {
            $name = collect(self::FIRST_NAMES)->random().' '.collect(self::LAST_NAMES)->random();

            do {
                $mobile = '09'.random_int(10, 39).random_int(1000000, 9999999);
            } while (isset($used[$mobile]));

            $used[$mobile] = true;

            $user = User::create([
                'name' => $name,
                'email' => 'customer'.$i.'@digino.test',
                'mobile' => $mobile,
                'password' => Hash::make('password'),
                'role' => UserRole::Customer->value,
                'is_active' => random_int(1, 100) <= 94,
                'email_verified_at' => random_int(1, 100) <= 80 ? now()->subDays(random_int(1, 300)) : null,
                'gender' => collect(['male', 'female', null])->random(),
                'newsletter' => random_int(1, 100) <= 45,
                'loyalty_points' => random_int(0, 4200),
                'created_at' => now()->subDays(random_int(1, 420)),
                'last_login_at' => now()->subDays(random_int(0, 30)),
                'last_login_ip' => '5.'.random_int(50, 250).'.'.random_int(1, 250).'.'.random_int(1, 250),
            ]);

            if (random_int(1, 100) <= 70) {
                $this->addresses($user, random_int(1, 2));
            }
        }

        $this->command?->info('  › '.User::count().' user accounts created (admin@digino.test / password).');
    }

    protected function addresses(User $user, int $count): void
    {
        $map = Iran::map();
        $provinces = array_keys($map);
        $labels = ['منزل', 'محل کار', 'منزل پدری', 'دفتر'];

        for ($i = 0; $i < $count; $i++) {
            $province = $provinces[array_rand($provinces)];
            $cities = $map[$province];

            Address::create([
                'user_id' => $user->id,
                'label' => $labels[$i] ?? 'نشانی',
                'receiver_name' => $user->name,
                'receiver_mobile' => $user->mobile,
                'province' => $province,
                'city' => $cities[array_rand($cities)],
                'line' => 'خیابان '.collect(['شریعتی', 'ولیعصر', 'آزادی', 'انقلاب', 'کارگر', 'فردوسی', 'سعدی'])->random()
                    .'، کوچه '.random_int(1, 40).'، ساختمان '.collect(['نیلوفر', 'ارغوان', 'یاس', 'بهار', 'پارسا'])->random(),
                'plate' => (string) random_int(1, 220),
                'unit' => (string) random_int(1, 14),
                'postal_code' => (string) random_int(1000000000, 9999999999),
                'is_default' => $i === 0,
            ]);
        }
    }
}
