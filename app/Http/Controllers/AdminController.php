<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', ['section' => 'dashboard', 'revenue' => Order::where('status', 'delivered')->sum('total'), 'orderCount' => Order::count(), 'productCount' => Product::count(), 'customerCount' => User::where('is_admin', false)->count(), 'lowStock' => Product::where('stock', '<', 5)->get(), 'orders' => Order::with('user')->latest()->take(6)->get(), 'chart' => collect(range(6, 0))->map(fn ($i) => ['date' => now()->subDays($i)->format('m/d'), 'total' => Order::whereDate('created_at', now()->subDays($i))->where('status', '!=', 'cancelled')->sum('total')])]);
    }

    public function products(Request $r)
    {
        $q = Product::with('category');
        if ($r->filled('q')) {
            $q->where(fn ($q) => $q->where('name', 'like', '%'.$r->string('q').'%')->orWhere('sku', 'like', '%'.$r->string('q').'%'));
        }

return view('admin.products', ['section' => 'products', 'products' => $q->latest()->paginate(12)->withQueryString()]);
    }

    public function productForm(?Product $product = null)
    {
        return view('admin.product-form', ['section' => 'products', 'product' => $product ?? new Product]);
    }

    public function saveProduct(Request $r, ?Product $product = null)
    {
        $d = $r->validate(['name' => 'required|string|max:200', 'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique('products')->ignore($product?->id)], 'sku' => ['required', 'alpha_dash', 'max:100', Rule::unique('products')->ignore($product?->id)], 'brand' => 'required|string|max:100', 'category_id' => 'required|exists:categories,id', 'price' => 'required|integer|min:1|max:10000000000', 'old_price' => 'nullable|integer|gte:price|max:10000000000', 'stock' => 'required|integer|min:0|max:1000000', 'description' => 'required|string|min:10|max:20000', 'image' => 'required|string|max:500', 'color' => 'required|string|max:50', 'active' => 'boolean', 'featured' => 'boolean', 'specs_json' => 'nullable|json']);
        $this->validImage($d['image']);
        $d['active'] = $r->boolean('active');
        $d['featured'] = $r->boolean('featured');
        if (! empty($d['specs_json'])) {
            $specs = json_decode($d['specs_json'], true);
            abort_unless(is_array($specs) && count($specs) <= 30, 422);
            foreach ($specs as $k => $v) {
                abort_unless(is_string($k) && is_scalar($v), 422);
            }$d['specs'] = $specs;
        }unset($d['specs_json']);
        if ($product) {
            $product->update($d);
        } else {
            Product::create($d);
        }

return response()->json(['message' => 'محصول ذخیره شد.', 'redirect' => '/admin/products']);
    }

    public function deleteProduct(Product $product)
    {
        $product->update(['active' => false]);

        return response()->json(['message' => 'محصول بایگانی و از فروشگاه پنهان شد.', 'reload' => true]);
    }

    public function orders(Request $r)
    {
        $q = Order::with('user');
        if (in_array($r->input('status'), ['processing', 'shipped', 'delivered', 'cancelled'])) {
            $q->where('status', $r->input('status'));
        }

return view('admin.orders', ['section' => 'orders', 'orders' => $q->latest()->paginate(15)->withQueryString()]);
    }

    public function order(Order $order)
    {
        return view('admin.order', ['section' => 'orders', 'order' => $order->load('items', 'user')]);
    }

    public function orderStatus(Request $r, Order $order, OrderService $service)
    {
        $d = $r->validate(['status' => 'required|in:processing,shipped,delivered,cancelled']);
        $service->transition($order, $d['status']);

        return response()->json(['message' => 'وضعیت سفارش به‌روز شد.', 'reload' => true]);
    }

    public function resource(Request $r, string $resource)
    {
        $cfg = config('admin.'.$resource);
        abort_unless($cfg, 404);
        $q = $cfg['model']::query();
        if ($resource === 'customers') {
            $q->where('is_admin', false);
        }if ($r->filled('q')) {
            $field = match ($resource) {
                'coupons' => 'code','articles','banners' => 'title','reviews' => 'body','tickets' => 'subject','subscribers' => 'email',default => 'name'
            };
            $q->where($field, 'like', '%'.$r->string('q').'%');
        }

return view('admin.resource', ['section' => $resource, 'cfg' => $cfg, 'rows' => $q->latest()->paginate(15)->withQueryString()]);
    }

    public function saveResource(Request $r, string $resource, ?int $id = null)
    {
        $cfg = config('admin.'.$resource);
        if ($resource === 'coupons' && $r->has('code')) {
            $r->merge(['code' => strtoupper(trim($r->input('code')))]);
        }abort_unless($cfg && count($cfg['fields']) && ($id || empty($cfg['readonly'])), 404);
        $model = $id ? $cfg['model']::findOrFail($id) : new $cfg['model'];
        $rules = [];
        foreach ($cfg['fields'] as $key => $f) {
            $rules[$key] = $f[2];
        }foreach (['slug', 'code'] as $key) {
            if (isset($rules[$key])) {
                $rules[$key] = [...explode('|', $rules[$key]), Rule::unique($model->getTable(), $key)->ignore($model->id)];
            }
        }$d = $r->validate($rules);
        foreach ($cfg['fields'] as $k => $f) {
            if ($f[1] === 'checkbox') {
                $d[$k] = $r->boolean($k);
            }
        }if (isset($d['image'])) {
            $this->validImage($d['image']);
        }if (isset($d['code'])) {
            $d['code'] = strtoupper($d['code']);
        }$model->fill($d)->save();

        return response()->json(['message' => 'تغییرات ذخیره شد.', 'reload' => true]);
    }

    public function deleteResource(string $resource, int $id)
    {
        $cfg = config('admin.'.$resource);
        abort_unless($cfg && ! in_array($resource, ['inventory', 'customers']), 404);
        $model = $cfg['model']::findOrFail($id);
        if ($resource === 'categories' && $model->products()->exists()) {
            return response()->json(['message' => 'ابتدا محصولات این دسته را به دسته دیگری منتقل کنید.'], 422);
        }$model->delete();

        return response()->json(['message' => 'مورد انتخاب‌شده حذف شد.', 'reload' => true]);
    }

    public function settings()
    {
        return view('admin.settings', ['section' => 'settings', 'settings' => Setting::pluck('value', 'key')]);
    }

    public function saveSettings(Request $r)
    {
        $d = $r->validate(['shop_name' => 'required|string|max:100', 'support_email' => 'nullable|email|max:190', 'support_phone' => 'nullable|string|max:30', 'address' => 'nullable|string|max:500', 'shipping_cost' => 'required|integer|min:0|max:10000000', 'free_shipping' => 'required|integer|min:0|max:1000000000']);
        foreach ($d as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

return response()->json(['message' => 'تنظیمات فروشگاه ذخیره شد.']);
    }

    public function upload(Request $r)
    {
        $r->validate(['image' => 'required|file|image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:max_width=6000,max_height=6000']);
        $file = $r->file('image');
        $name = bin2hex(random_bytes(20)).'.'.$file->extension();
        $file->move(public_path('uploads'), $name);

        return response()->json(['path' => '/uploads/'.$name]);
    }

    private function validImage(?string $path): void
    {
        if (! $path) {
            return;
        }abort_unless(preg_match('~^/(assets/images|uploads)/[a-zA-Z0-9_.-]+\.(jpg|jpeg|png|webp|svg)$~',$path) && is_file(public_path($path)),422,'مسیر تصویر معتبر نیست.');
    }
}
