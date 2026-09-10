<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class SettingAdminController extends Controller
{
    public function index(Request $request){
        if($request->ajax()) return response()->json(['message'=>'SettingAdminController list']);
        return view('admin.settingadmincontroller', ['title' => 'SettingAdminController']);
    }
    public function show($id){ return view('admin.show', compact('id')); }
}
