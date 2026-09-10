<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class CustomerAdminController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) return response()->json(['message'=>'CustomerAdminController list']);
        return view('admin.customeradmincontroller', ['title' => 'CustomerAdminController']);
    }
    public function show($id){ return view('admin.show', compact('id')); }
}
