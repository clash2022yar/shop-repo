<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CheckoutController extends Controller
{
    public function index(){ $cart = session()->get('cart',[]); if(empty($cart)) return redirect()->route('cart.index'); return view('front.checkout', compact('cart')); }
    public function store(Request $request){
        $request->validate(['address'=>'required|string','payment_method'=>'required|in:online,cod']);
        $cart = session()->get('cart',[]);
        return DB::transaction(function() use($request,$cart){
            $total = collect($cart)->sum(fn($i)=>$i['price']*$i['quantity']);
            $order = Order::create(['user_id'=>auth()->id(),'total'=>$total,'status'=>'pending','address'=>$request->address,'payment_method'=>$request->payment_method]);
            foreach($cart as $item){ OrderItem::create(['order_id'=>$order->id,'product_id'=>$item['id'],'quantity'=>$item['quantity'],'price'=>$item['price']]); }
            session()->forget('cart');
            if(request()->ajax()) return response()->json(['message'=>'سفارش ثبت شد','order_id'=>$order->id]);
            return redirect()->route('checkout.success',$order);
        });
    }
    public function success(Order $order){ return view('front.checkout-success', compact('order')); }
}
