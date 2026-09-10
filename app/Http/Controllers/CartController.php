<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
class CartController extends Controller
{
    public function index(Request $request){
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($i)=>$i['price']*$i['quantity']);
        if($request->ajax()) return response()->json(['cart'=>$cart,'total'=>$total]);
        return view('front.cart', compact('cart','total'));
    }
    public function add(Request $request){
        $request->validate(['product_id'=>'required|exists:products,id','quantity'=>'nullable|integer|min:1']);
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $id = $product->id;
        if(isset($cart[$id])) $cart[$id]['quantity'] += $request->quantity ?? 1;
        else $cart[$id] = ['id'=>$id,'name'=>$product->name,'slug'=>$product->slug,'price'=>$product->final_price,'image'=>$product->images[0]??null,'quantity'=>$request->quantity??1];
        session()->put('cart',$cart);
        return response()->json(['message'=>'به سبد خرید اضافه شد','cart'=>$cart,'count'=>count($cart)]);
    }
    public function update(Request $request,$id){
        $cart = session()->get('cart', []);
        if(isset($cart[$id])){ $cart[$id]['quantity'] = max(1, (int)$request->quantity); session()->put('cart',$cart); }
        return response()->json(['cart'=>$cart]);
    }
    public function remove($id){
        $cart = session()->get('cart', []); unset($cart[$id]); session()->put('cart',$cart);
        return response()->json(['message'=>'حذف شد','cart'=>$cart]);
    }
    public function applyCoupon(Request $request){
        $code = $request->coupon;
        $coupon = \App\Models\Coupon::where('code',$code)->where('is_active',true)->first();
        if(!$coupon) return response()->json(['message'=>'کوپن نامعتبر است'],422);
        return response()->json(['message'=>'کوپن اعمال شد','discount'=>$coupon->discount_percent]);
    }
}
