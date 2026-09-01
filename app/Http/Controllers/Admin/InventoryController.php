<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $threshold = config('digino.catalog.low_stock_threshold');

        return view('admin.inventory.index', [
            'products' => $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString(),
            'stats' => [
                'total_units' => (int) Product::sum('stock'),
                'stock_value' => (int) Product::selectRaw('SUM(stock * price) as v')->value('v'),
                'low' => Product::whereBetween('stock', [1, $threshold])->count(),
                'out' => Product::where('stock', 0)->count(),
            ],
            'threshold' => $threshold,
        ]);
    }

    public function table(Request $request)
    {
        $products = $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString();

        return $this->ok('', [
            'html' => view('admin.inventory.partials.rows', compact('products'))->render(),
            'pagination' => $products->links()->render(),
            'total' => $products->total(),
        ]);
    }

    public function adjust(Request $request, Product $product)
    {
        $request->merge(['quantity' => en_number($request->input('quantity'))]);

        $data = $request->validate([
            'mode' => ['required', 'in:set,add,subtract'],
            'quantity' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'quantity.required' => 'تعداد را وارد کنید.',
            'quantity.min' => 'تعداد نمی‌تواند منفی باشد.',
        ]);

        $before = (int) $product->stock;

        $after = match ($data['mode']) {
            'set' => (int) $data['quantity'],
            'add' => $before + (int) $data['quantity'],
            'subtract' => max(0, $before - (int) $data['quantity']),
        };

        if ($after === $before) {
            return $this->fail('موجودی تغییری نکرد.');
        }

        $product->update(['stock' => $after]);

        InventoryMovement::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'type' => $after > $before ? 'in' : 'out',
            'quantity' => $after - $before,
            'stock_after' => $after,
            'reference' => 'اصلاح دستی',
            'note' => $data['note'] ?? null,
        ]);

        ActivityLog::record('inventory.adjust', $product,
            "موجودی «{$product->name}» از {$before} به {$after} تغییر کرد.");

        return $this->ok('موجودی به‌روزرسانی شد.', [
            'stock' => $after,
            'label' => $product->fresh()->stock_label,
        ]);
    }

    public function history(Product $product)
    {
        return $this->ok('', [
            'html' => view('admin.inventory.partials.history', [
                'movements' => $product->movements()->with('user')->limit(30)->get(),
                'product' => $product,
            ])->render(),
        ]);
    }

    public function movements(Request $request)
    {
        return view('admin.inventory.movements', [
            'movements' => InventoryMovement::with(['product', 'user'])
                ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
                ->latest()->paginate(30)->withQueryString(),
        ]);
    }

    protected function query(Request $request)
    {
        $threshold = config('digino.catalog.low_stock_threshold');

        return Product::with(['category', 'brand', 'images'])
            ->when($request->filled('q'), fn ($q) => $q->search($request->input('q')))
            ->when($request->input('filter') === 'low', fn ($q) => $q->whereBetween('stock', [1, $threshold]))
            ->when($request->input('filter') === 'out', fn ($q) => $q->where('stock', 0))
            ->when($request->input('filter') === 'ok', fn ($q) => $q->where('stock', '>', $threshold))
            ->orderBy('stock');
    }
}
