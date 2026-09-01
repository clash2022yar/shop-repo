<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartAjaxController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function show()
    {
        return $this->ok('', [
            'summary' => $this->cart->summary(),
            'html' => view('partials.cart-lines', ['items' => $this->cart->items()])->render(),
        ]);
    }

    /** Small dropdown rendered from the header cart button. */
    public function mini()
    {
        return $this->ok('', [
            'summary' => $this->cart->summary(),
            'html' => view('partials.mini-cart', [
                'items' => $this->cart->items()->take(5),
                'summary' => $this->cart->summary(),
            ])->render(),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $variant = isset($data['variant_id'])
            ? ProductVariant::where('product_id', $product->id)->find($data['variant_id'])
            : null;

        $result = $this->cart->add($product, $variant, (int) ($data['quantity'] ?? 1));

        if (! $result['ok']) {
            return $this->fail($result['message'], ['summary' => $this->cart->summary()]);
        }

        return $this->ok($result['message'], [
            'summary' => $this->cart->summary(),
            'product' => [
                'name' => $product->name,
                'image' => asset($product->primary_image),
                'price' => toman($product->price),
                'url' => route('products.show', $product->slug),
            ],
        ]);
    }

    public function update(Request $request, CartItem $item)
    {
        $this->authorizeItem($item);

        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:10']]);

        $result = $this->cart->updateQuantity($item, (int) $data['quantity']);

        return $this->ok($result['message'], [
            'summary' => $this->cart->summary(),
            'quantity' => $result['quantity'],
            'line_total' => $result['quantity'] > 0 ? toman($item->fresh()->line_total) : '0',
            'removed' => $result['quantity'] === 0,
        ]);
    }

    public function destroy(CartItem $item)
    {
        $this->authorizeItem($item);
        $name = $item->product->name;
        $this->cart->remove($item);

        return $this->ok('«'.$name.'» از سبد خرید حذف شد.', ['summary' => $this->cart->summary()]);
    }

    public function select(Request $request, CartItem $item)
    {
        $this->authorizeItem($item);
        $this->cart->toggleSelection($item, $request->boolean('selected'));

        return $this->ok('', ['summary' => $this->cart->summary()]);
    }

    public function selectAll(Request $request)
    {
        $this->cart->selectAll($request->boolean('selected'));

        return $this->ok('', ['summary' => $this->cart->summary()]);
    }

    public function destroySelected()
    {
        $count = $this->cart->removeSelected();

        return $this->ok(fa_number($count).' کالا از سبد خرید حذف شد.', [
            'summary' => $this->cart->summary(),
        ]);
    }

    public function clear()
    {
        $this->cart->clear();

        return $this->ok('سبد خرید خالی شد.', ['summary' => $this->cart->summary()]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'max:40']], [
            'code.required' => 'کد تخفیف را وارد کنید.',
        ]);

        $result = $this->cart->applyCoupon($request->string('code')->toString());

        return $result['ok']
            ? $this->ok($result['message'], ['summary' => $this->cart->summary()])
            : $this->fail($result['message'], ['summary' => $this->cart->summary()]);
    }

    public function removeCoupon()
    {
        $this->cart->removeCoupon();

        return $this->ok('کد تخفیف حذف شد.', ['summary' => $this->cart->summary()]);
    }

    /** A cart line may only be touched by the visitor who owns the cart. */
    protected function authorizeItem(CartItem $item): void
    {
        $cart = $this->cart->cart(false);

        abort_unless($cart && $item->cart_id === $cart->id, 403, 'این کالا در سبد خرید شما نیست.');
    }
}
