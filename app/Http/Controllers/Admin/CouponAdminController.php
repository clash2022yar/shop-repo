<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class CouponAdminController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) return response()->json(['message'=>'CouponAdminController list']);
        return view('admin.couponadmincontroller', ['title' => 'CouponAdminController']);
    }
    public function show($id){ return view('admin.show', compact('id')); }
}
