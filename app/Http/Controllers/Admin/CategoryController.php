<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.categories.index', [
            'categories' => Category::roots()
                ->with(['children' => fn ($q) => $q->withCount('products')->orderBy('sort_order')])
                ->withCount('products')
                ->orderBy('sort_order')->get(),
            'flat' => Category::orderBy('name')->get(),
            'total' => Category::count(),
        ]);
    }

    public function show(Category $category)
    {
        return $this->ok('', ['category' => $category->load('parent')]);
    }

    public function store(Request $request)
    {
        $category = Category::create($this->validated($request));

        ActivityLog::record('category.created', $category, "دسته‌بندی «{$category->name}» ایجاد شد.");

        return $this->ok('دسته‌بندی ایجاد شد.', ['id' => $category->id]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);

        // A category may not become its own descendant.
        if (($data['parent_id'] ?? null) && in_array((int) $data['parent_id'], $category->descendantIds(), true)) {
            return $this->fail('یک دسته‌بندی نمی‌تواند زیرمجموعهٔ خودش باشد.');
        }

        $category->update($data);

        ActivityLog::record('category.updated', $category, "دسته‌بندی «{$category->name}» ویرایش شد.");

        return $this->ok('دسته‌بندی به‌روزرسانی شد.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return $this->fail('این دسته‌بندی دارای کالا است و قابل حذف نیست. ابتدا کالاها را جابه‌جا کنید.');
        }

        if ($category->children()->exists()) {
            return $this->fail('ابتدا زیر‌دسته‌های این دسته‌بندی را حذف یا جابه‌جا کنید.');
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::record('category.deleted', null, "دسته‌بندی «{$name}» حذف شد.");

        return $this->ok('دسته‌بندی حذف شد.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => ['required', 'array']]);

        foreach ($request->input('order') as $position => $id) {
            Category::whereKey($id)->update(['sort_order' => $position]);
        }

        return $this->ok('ترتیب دسته‌بندی‌ها ذخیره شد.');
    }

    protected function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150', Rule::unique('categories')->ignore($category?->id)],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'string', 'max:255'],
            'banner' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'نام دسته‌بندی را وارد کنید.',
            'slug.unique' => 'این نشانی یکتا قبلاً استفاده شده است.',
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'show_in_menu' => $request->boolean('show_in_menu'),
        ];
    }
}
