<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class InventoryAdminController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) return response()->json(['message'=>'InventoryAdminController list']);
        return view('admin.inventoryadmincontroller', ['title' => 'InventoryAdminController']);
    }
    public function show($id){ return view('admin.show', compact('id')); }
}
