<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index(){ $orders = auth()->user()->orders()->latest()->take(4)->get(); return view('user.dashboard', compact('orders')); }
    public function wishlist(){ $wishlist = auth()->user()->wishlist()->paginate(12); return view('user.wishlist', compact('wishlist')); }
    public function addresses(){ $addresses = auth()->user()->addresses; return view('user.addresses', compact('addresses')); }
    public function profile(){ return view('user.profile', ['user'=>auth()->user()]); }
    public function updateProfile(Request $request){
        $request->validate(['name'=>'required','email'=>'required|email','phone'=>'nullable']);
        auth()->user()->update($request->only('name','email','phone'));
        return back()->with('success','پروفایل بروز شد');
    }
}
