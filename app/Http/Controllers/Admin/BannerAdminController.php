<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class BannerAdminController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) return response()->json(['message'=>'BannerAdminController list']);
        return view('admin.banneradmincontroller', ['title' => 'BannerAdminController']);
    }
    public function show($id){ return view('admin.show', compact('id')); }
}
