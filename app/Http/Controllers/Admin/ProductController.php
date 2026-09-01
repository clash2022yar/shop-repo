<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.products.index', [
            'products' => $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString(),
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('name')->get(),
            'counts' => [
                'all' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
                'inactive' => Product::where('is_active', false)->count(),
                'out' => Product::where('stock', 0)->count(),
                'special' => Product::where('is_special', true)->count(),
            ],
        ]);
    }

    /** Table body only — used for AJAX filtering / paging. */
    public function table(Request $request)
    {
        $products = $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString();

        return $this->ok('', [
            'html' => view('admin.products.partials.rows', compact('products'))->render(),
            'pagination' => $products->links()->render(),
            'total' => $products->total(),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $product = DB::transaction(function () use ($data, $request) {
            $product = Product::create($data);
            $this->syncRelated($product, $request);

            InventoryMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => 'in',
                'quantity' => $product->stock,
                'stock_after' => $product->stock,
                'reference' => 'ثبت اولیه',
                'note' => 'ایجاد کالای جدید',
            ]);

            return $product;
        });

        ActivityLog::record('product.created', $product, "کالای «{$product->name}» ایجاد شد.");

        return $this->ok('کالا با موفقیت ثبت شد.', [
            'redirect' => route('admin.products.index'),
            'id' => $product->id,
        ]);
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product->load(['images', 'variants', 'attributes']),
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);
        $oldStock = $product->stock;

        DB::transaction(function () use ($product, $data, $request, $oldStock) {
            $product->update($data);
            $this->syncRelated($product, $request);

            if ($product->stock !== $oldStock) {
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'adjust',
                    'quantity' => $product->stock - $oldStock,
                    'stock_after' => $product->stock,
                    'reference' => 'ویرایش کالا',
                    'note' => 'اصلاح موجودی از طریق فرم ویرایش',
                ]);
            }
        });

        ActivityLog::record('product.updated', $product, "کالای «{$product->name}» ویرایش شد.");

        return $this->ok('تغییرات ذخیره شد.', ['redirect' => route('admin.products.index')]);
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        ActivityLog::record('product.deleted', null, "کالای «{$name}» حذف شد.");

        return $this->ok('کالا حذف شد.');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return $this->ok($product->is_active ? 'کالا فعال شد.' : 'کالا غیرفعال شد.', [
            'is_active' => $product->is_active,
        ]);
    }

    public function duplicate(Product $product)
    {
        $copy = $product->replicate(['slug', 'sku', 'sold_count', 'views_count', 'rating', 'reviews_count']);
        $copy->name = $product->name.' (کپی)';
        $copy->slug = null;
        $copy->sku = null;
        $copy->is_active = false;
        $copy->save();

        foreach ($product->images as $image) {
            $copy->images()->create($image->only(['path', 'alt', 'is_primary', 'sort_order']));
        }

        foreach ($product->attributes as $attr) {
            $copy->attributes()->create($attr->only(['group', 'name', 'value', 'is_key', 'sort_order']));
        }

        return $this->ok('یک نسخه از کالا ساخته شد.', ['redirect' => route('admin.products.edit', $copy)]);
    }

    /** Bulk activate / deactivate / delete from the table checkboxes. */
    public function bulk(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,special,unspecial'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ], ['ids.required' => 'هیچ کالایی انتخاب نشده است.']);

        $query = Product::whereIn('id', $data['ids']);
        $count = (clone $query)->count();

        match ($data['action']) {
            'activate' => $query->update(['is_active' => true]),
            'deactivate' => $query->update(['is_active' => false]),
            'special' => $query->update(['is_special' => true]),
            'unspecial' => $query->update(['is_special' => false]),
            'delete' => $query->delete(),
        };

        return $this->ok(fa_number($count).' کالا به‌روزرسانی شد.');
    }

    // -------------------------------------------------------------- helpers
    protected function query(Request $request)
    {
        return Product::query()
            ->with(['category', 'brand', 'images'])
            ->when($request->filled('q'), fn ($q) => $q->search($request->input('q')))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->input('category_id')))
            ->when($request->filled('brand_id'), fn ($q) => $q->where('brand_id', $request->input('brand_id')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->input('status') === 'out', fn ($q) => $q->where('stock', 0))
            ->when($request->input('status') === 'special', fn ($q) => $q->where('is_special', true))
            ->sorted($request->input('sort', 'newest'));
    }

    protected function validated(Request $request, ?Product $product = null): array
    {
        foreach (['price', 'compare_at_price', 'stock', 'discount_percent'] as $numeric) {
            if ($request->filled($numeric)) {
                $request->merge([$numeric => (int) preg_replace('/\D/', '', en_number($request->input($numeric)))]);
            }
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'name_en' => ['nullable', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('products')->ignore($product?->id)],
            'sku' => ['nullable', 'string', 'max:40', Rule::unique('products')->ignore($product?->id)],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'subtitle' => ['nullable', 'string', 'max:250'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:20000'],
            'price' => ['required', 'integer', 'min:0'],
            'compare_at_price' => ['nullable', 'integer', 'min:0', 'gte:price'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:99'],
            'stock' => ['required', 'integer', 'min:0'],
            'max_per_order' => ['nullable', 'integer', 'min:1', 'max:20'],
            'warranty' => ['nullable', 'string', 'max:150'],
            'shipping_weight' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_special' => ['nullable', 'boolean'],
            'is_digino_seller' => ['nullable', 'boolean'],
            'has_pickup' => ['nullable', 'boolean'],
            'free_shipping' => ['nullable', 'boolean'],
            'special_ends_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'نام کالا را وارد کنید.',
            'category_id.required' => 'دسته‌بندی را انتخاب کنید.',
            'price.required' => 'قیمت کالا را وارد کنید.',
            'compare_at_price.gte' => 'قیمت پیش از تخفیف باید بزرگ‌تر یا مساوی قیمت فروش باشد.',
            'stock.required' => 'موجودی انبار را وارد کنید.',
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'is_special' => $request->boolean('is_special'),
            'is_digino_seller' => $request->boolean('is_digino_seller'),
            'has_pickup' => $request->boolean('has_pickup'),
            'free_shipping' => $request->boolean('free_shipping'),
        ];
    }

    /** Replace the images / attributes / variants sent with the form. */
    protected function syncRelated(Product $product, Request $request): void
    {
        if ($request->has('images')) {
            $product->images()->delete();

            foreach (array_values(array_filter((array) $request->input('images'))) as $i => $path) {
                $product->images()->create([
                    'path' => $path,
                    'alt' => $product->name,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        if ($request->has('attributes')) {
            $product->attributes()->delete();

            foreach ((array) $request->input('attributes') as $i => $attr) {
                if (blank($attr['name'] ?? null) || blank($attr['value'] ?? null)) {
                    continue;
                }

                $product->attributes()->create([
                    'group' => $attr['group'] ?? 'مشخصات کلی',
                    'name' => $attr['name'],
                    'value' => $attr['value'],
                    'is_key' => (bool) ($attr['is_key'] ?? false),
                    'sort_order' => $i,
                ]);
            }
        }

        if ($request->has('variants')) {
            $keep = [];

            foreach ((array) $request->input('variants') as $i => $variant) {
                if (blank($variant['title'] ?? null)) {
                    continue;
                }

                $row = $product->variants()->updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    [
                        'title' => $variant['title'],
                        'color_name' => $variant['color_name'] ?? null,
                        'color_hex' => $variant['color_hex'] ?? null,
                        'option_name' => $variant['option_name'] ?? null,
                        'option_value' => $variant['option_value'] ?? null,
                        'price_diff' => (int) ($variant['price_diff'] ?? 0),
                        'stock' => (int) ($variant['stock'] ?? 0),
                        'is_active' => (bool) ($variant['is_active'] ?? true),
                        'sort_order' => $i,
                    ]
                );

                $keep[] = $row->id;
            }

            $product->variants()->whereNotIn('id', $keep)->delete();
        }
    }
}
