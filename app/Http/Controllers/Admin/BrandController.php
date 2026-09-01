<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.brands.index', [
            'brands' => Brand::withCount('products')
                ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->input('q').'%')
                    ->orWhere('name_en', 'like', '%'.$request->input('q').'%'))
                ->orderByDesc('is_featured')->orderBy('name')
                ->paginate(config('digino.admin.per_page'))->withQueryString(),
        ]);
    }

    public function show(Brand $brand)
    {
        return $this->ok('', ['brand' => $brand]);
    }

    public function store(Request $request)
    {
        $brand = Brand::create($this->validated($request));

        return $this->ok('برند ثبت شد.', ['id' => $brand->id]);
    }

    public function update(Request $request, Brand $brand)
    {
        $brand->update($this->validated($request, $brand));

        return $this->ok('برند به‌روزرسانی شد.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->exists()) {
            return $this->fail('این برند دارای کالا است و قابل حذف نیست.');
        }

        $brand->delete();

        return $this->ok('برند حذف شد.');
    }

    protected function validated(Request $request, ?Brand $brand = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'name_en' => ['nullable', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150', Rule::unique('brands')->ignore($brand?->id)],
            'logo' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], ['name.required' => 'نام برند را وارد کنید.']) + [
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
