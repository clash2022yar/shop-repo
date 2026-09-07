<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'stats' => [
                'sales' => Order::where('payment_status', 'paid')->sum('total'),
                'orders' => Order::count(),
                'products' => Product::count(),
                'customers' => User::where('is_admin', false)->count(),
            ],
            'recent' => Order::with('user')->latest()->take(6)->get(),
            'lowStock' => Product::where('stock', '<', 10)->take(6)->get(),
        ]);
    }

    public function products()
    {
        return view('admin.resource', [
            'type' => 'products',
            'title' => 'محصولات',
            'items' => Product::with('category')->latest()->paginate(12),
            'columns' => ['محصول', 'دسته‌بندی', 'قیمت', 'موجودی', 'وضعیت'],
        ]);
    }

    public function productForm(?Product $product = null)
    {
        return view('admin.product-form', ['product' => $product, 'categories' => Category::all()]);
    }

    public function saveProduct(Request $request, ?Product $product = null)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'sku' => ['required', 'string', 'max:80', Rule::unique('products')->ignore($product)],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'old_price' => ['nullable', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'brand' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $name = Str::uuid().'.'.$request->file('image')->extension();
            $request->file('image')->move(public_path('images/products'), $name);
            $data['image'] = 'images/products/'.$name;
        } elseif (! $product) {
            $data['image'] = 'images/products/galaxy-s24.webp';
        }

        $data['slug'] = Str::slug($data['name']).'-'.($product?->id ?? Str::lower(Str::random(5)));
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($product) {
            $product->update($data);
        } else {
            Product::create($data);
        }

        return response()->json(['message' => 'محصول با موفقیت ذخیره شد', 'redirect' => route('admin.products')]);
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'محصول حذف شد']);
    }

    public function resource(string $type)
    {
        [$items, $title] = match ($type) {
            'categories' => [Category::withCount('products')->latest()->paginate(15), 'دسته‌بندی‌ها'],
            'orders' => [Order::with('user')->latest()->paginate(15), 'سفارش‌ها'],
            'customers' => [User::where('is_admin', false)->latest()->paginate(15), 'مشتریان'],
            'coupons' => [Coupon::latest()->paginate(15), 'کدهای تخفیف'],
            'reviews' => [Review::with(['user', 'product'])->latest()->paginate(15), 'دیدگاه‌ها'],
            'inventory' => [Product::latest()->paginate(15), 'انبار'],
            'banners' => [Banner::latest()->paginate(15), 'بنرها'],
            'settings' => [collect(), 'تنظیمات'],
            default => abort(404),
        };

        return view('admin.resource', compact('type', 'title', 'items') + ['columns' => []]);
    }

    public function order(Order $order)
    {
        return view('admin.order', ['order' => $order->load(['items', 'user'])]);
    }

    public function updateOrder(Order $order, Request $request)
    {
        $order->update($request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled']));

        return response()->json(['message' => 'وضعیت سفارش به‌روزرسانی شد']);
    }

    public function quickStore(string $type, Request $request)
    {
        match ($type) {
            'categories' => Category::create($request->validate([
                'name' => ['required', 'string', 'max:100'],
                'description' => ['nullable', 'string', 'max:500'],
            ]) + ['slug' => Str::slug($request->name).'-'.Str::lower(Str::random(4)), 'is_active' => true]),
            'coupons' => Coupon::create($request->validate([
                'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
                'type' => ['required', 'in:percent,fixed'],
                'value' => ['required', 'integer', 'min:1'],
                'min_order' => ['nullable', 'integer', 'min:0'],
            ]) + ['is_active' => true]),
            'banners' => Banner::create($request->validate([
                'title' => ['required', 'string', 'max:160'],
                'subtitle' => ['nullable', 'string', 'max:250'],
                'url' => ['required', 'string', 'max:255'],
                'position' => ['required', 'in:home'],
            ]) + ['is_active' => true]),
            default => abort(404),
        };

        return response()->json(['message' => 'آیتم جدید ذخیره شد', 'reload' => true]);
    }

    public function saveSettings(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'support_phone' => ['required', 'string', 'max:30'],
            'support_email' => ['required', 'email'],
            'shipping_cost' => ['required', 'integer', 'min:0'],
            'store_description' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()],
            );
        }

        return response()->json(['message' => 'تنظیمات فروشگاه ذخیره شد']);
    }

    public function action(string $type, int $id, Request $request)
    {
        match ($type) {
            'reviews' => Review::findOrFail($id)->update(['is_approved' => $request->boolean('approved')]),
            'inventory' => Product::findOrFail($id)->update($request->validate(['stock' => ['required', 'integer', 'min:0']])),
            default => abort(404),
        };

        return response()->json(['message' => 'تغییرات ذخیره شد']);
    }
}
