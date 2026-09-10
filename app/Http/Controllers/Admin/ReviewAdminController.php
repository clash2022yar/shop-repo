<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class ReviewAdminController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) return response()->json(['message'=>'ReviewAdminController list']);
        return view('admin.reviewadmincontroller', ['title' => 'ReviewAdminController']);
    }
    public function show($id){ return view('admin.show', compact('id')); }
}
