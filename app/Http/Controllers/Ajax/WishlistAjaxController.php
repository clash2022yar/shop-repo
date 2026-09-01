<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistAjaxController extends Controller
{
    public function toggle(Request $request, Product $product)
    {
        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();
            $active = false;
            $message = 'از علاقه‌مندی‌ها حذف شد.';
        } else {
            Wishlist::create(['user_id' => $request->user()->id, 'product_id' => $product->id]);
            $active = true;
            $message = 'به علاقه‌مندی‌ها اضافه شد.';
        }

        return $this->ok($message, [
            'active' => $active,
            'count' => $request->user()->wishlists()->count(),
        ]);
    }

    public function index(Request $request)
    {
        return $this->ok('', [
            'ids' => $request->user()->wishlists()->pluck('product_id'),
        ]);
    }
}
