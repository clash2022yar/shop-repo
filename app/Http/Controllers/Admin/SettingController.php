<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /** The editable settings, grouped exactly as the UI shows them. */
    public const SCHEMA = [
        'general' => [
            'site_name' => ['label' => 'نام فروشگاه', 'type' => 'string'],
            'site_tagline' => ['label' => 'شعار فروشگاه', 'type' => 'string'],
            'site_description' => ['label' => 'توضیح کوتاه (متا)', 'type' => 'text'],
            'support_phone' => ['label' => 'تلفن پشتیبانی', 'type' => 'string'],
            'support_email' => ['label' => 'ایمیل پشتیبانی', 'type' => 'string'],
            'address' => ['label' => 'نشانی', 'type' => 'text'],
            'working_hours' => ['label' => 'ساعات کاری', 'type' => 'string'],
        ],
        'shop' => [
            'free_shipping_from' => ['label' => 'ارسال رایگان از مبلغ (تومان)', 'type' => 'int'],
            'shipping_cost' => ['label' => 'هزینه ارسال پیش‌فرض (تومان)', 'type' => 'int'],
            'low_stock_threshold' => ['label' => 'آستانه هشدار موجودی', 'type' => 'int'],
            'max_cart_qty' => ['label' => 'حداکثر تعداد هر کالا در سبد', 'type' => 'int'],
            'guest_checkout' => ['label' => 'اجازه خرید بدون ثبت‌نام', 'type' => 'bool'],
            'auto_approve_reviews' => ['label' => 'انتشار خودکار دیدگاه‌ها', 'type' => 'bool'],
        ],
        'social' => [
            'instagram' => ['label' => 'اینستاگرام', 'type' => 'string'],
            'telegram' => ['label' => 'تلگرام', 'type' => 'string'],
            'linkedin' => ['label' => 'لینکدین', 'type' => 'string'],
            'twitter' => ['label' => 'ایکس (توییتر)', 'type' => 'string'],
        ],
        'maintenance' => [
            'maintenance_mode' => ['label' => 'حالت تعمیر و نگهداری', 'type' => 'bool'],
            'maintenance_message' => ['label' => 'پیام حالت تعمیر', 'type' => 'text'],
        ],
    ];

    public function index()
    {
        return view('admin.settings.index', [
            'schema' => self::SCHEMA,
            'values' => Setting::all_cached(),
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];
        $types = [];

        foreach (self::SCHEMA as $group => $fields) {
            foreach ($fields as $key => $meta) {
                $types[$key] = ['type' => $meta['type'], 'group' => $group, 'label' => $meta['label']];

                $rules[$key] = match ($meta['type']) {
                    'int' => ['nullable', 'integer', 'min:0'],
                    'bool' => ['nullable', 'boolean'],
                    'text' => ['nullable', 'string', 'max:2000'],
                    default => ['nullable', 'string', 'max:255'],
                };
            }
        }

        $data = $request->validate($rules);

        foreach ($types as $key => $meta) {
            $value = $meta['type'] === 'bool'
                ? ($request->boolean($key) ? '1' : '0')
                : ($data[$key] ?? null);

            if ($meta['type'] === 'int' && $value !== null) {
                $value = (string) (int) preg_replace('/\D/', '', en_number((string) $value));
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $meta['group'], 'type' => $meta['type'], 'label' => $meta['label']]
            );
        }

        ActivityLog::record('settings.updated', null, 'تنظیمات فروشگاه به‌روزرسانی شد.');

        return $this->ok('تنظیمات با موفقیت ذخیره شد.');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');

        return $this->ok('حافظه پنهان فروشگاه پاک شد.');
    }
}
