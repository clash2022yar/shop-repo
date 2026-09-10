<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
class AdminDashboardController extends Controller
{
    public function index(){
        $stats = [
            'orders' => Order::count(),
            'products' => Product::count(),
            'users' => User::count(),
            'revenue' => Order::where('status','completed')->sum('total'),
        ];
        $recentOrders = Order::latest()->take(6)->get();
        $lowStock = Product::where('stock','<',5)->take(6)->get();
        return view('admin.dashboard', compact('stats','recentOrders','lowStock'));
    }
}
