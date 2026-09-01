<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.banners.index', [
            'banners' => Banner::when($request->filled('position'),
                fn ($q) => $q->where('position', $request->input('position')))
                ->orderBy('position')->orderBy('sort_order')->get()->groupBy('position'),
            'positions' => [
                'hero' => 'اسلایدر اصلی',
                'promo-right' => 'بنر تبلیغاتی راست',
                'promo-left' => 'بنر تبلیغاتی چپ',
                'strip' => 'نوار تبلیغاتی',
                'category' => 'سر دسته‌بندی',
                'sidebar' => 'ستون کناری',
            ],
            'total' => Banner::count(),
            'activeCount' => Banner::where('is_active', true)->count(),
        ]);
    }

    public function show(Banner $banner)
    {
        return $this->ok('', ['banner' => $banner]);
    }

    public function store(Request $request)
    {
        $banner = Banner::create($this->validated($request));

        return $this->ok('بنر ثبت شد.', ['id' => $banner->id]);
    }

    public function update(Request $request, Banner $banner)
    {
        $banner->update($this->validated($request));

        return $this->ok('بنر به‌روزرسانی شد.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return $this->ok('بنر حذف شد.');
    }

    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        return $this->ok($banner->is_active ? 'بنر فعال شد.' : 'بنر غیرفعال شد.', [
            'is_active' => $banner->is_active,
        ]);
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:200'],
            'caption' => ['nullable', 'string', 'max:250'],
            'cta_label' => ['nullable', 'string', 'max:60'],
            'image' => ['required', 'string', 'max:255'],
            'mobile_image' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'in:hero,promo-right,promo-left,strip,category,sidebar'],
            'bg_color' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
        ], [
            'image.required' => 'نشانی تصویر بنر را وارد کنید.',
            'position.required' => 'جایگاه نمایش بنر را انتخاب کنید.',
            'ends_at.after' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
