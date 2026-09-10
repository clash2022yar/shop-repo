<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
class CategoryAdminController extends Controller
{
    public function index(Request $request){ $categories = Category::withCount('products')->paginate(15); return view('admin.categories.index', compact('categories')); }
    public function create(){ return view('admin.categories.create'); }
    public function store(Request $request){
        $data = $request->validate(['name'=>'required','slug'=>'nullable']);
        $data['slug'] = $data['slug'] ?? \Str::slug($data['name']);
        Category::create($data);
        return redirect()->route('admin.categories.index');
    }
    public function edit(Category $category){ return view('admin.categories.edit', compact('category')); }
    public function update(Request $request, Category $category){ $category->update($request->validate(['name'=>'required'])); return back(); }
    public function destroy(Category $category){ $category->delete(); return response()->json(['message'=>'حذف شد']); }
}
