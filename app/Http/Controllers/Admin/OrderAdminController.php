<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class OrderAdminController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) return response()->json(['message'=>'OrderAdminController list']);
        return view('admin.orderadmincontroller', ['title' => 'OrderAdminController']);
    }
    public function show($id){ return view('admin.show', compact('id')); }
}
