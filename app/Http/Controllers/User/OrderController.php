<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Order;
class OrderController extends Controller
{
    public function index(){ $orders = auth()->user()->orders()->latest()->paginate(10); return view('user.orders', compact('orders')); }
    public function show(Order $order){ $this->authorize('view',$order); return view('user.order-details', compact('order')); }
}
