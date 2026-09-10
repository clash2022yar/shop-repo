<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
class ProductAdminController extends Controller
{
    public function index(Request $request){
        $products = Product::with('category')->latest()->paginate(15);
        if($request->ajax()) return response()->json(['products'=>$products]);
        return view('admin.products.index', compact('products'));
    }
    public function create(){ $categories = Category::all(); return view('admin.products.create', compact('categories')); }
    public function store(Request $request){
        $data = $request->validate(['name'=>'required','price'=>'required|integer','category_id'=>'required|exists:categories,id','stock'=>'required|integer']);
        $data['slug'] = \Str::slug($request->name).'-'.uniqid();
        $product = Product::create($data);
        return $request->ajax()? response()->json(['message'=>'محصول ساخته شد','product'=>$product]) : redirect()->route('admin.products.index');
    }
    public function edit(Product $product){ $categories = Category::all(); return view('admin.products.edit', compact('product','categories')); }
    public function update(Request $request, Product $product){
        $product->update($request->validate(['name'=>'required','price'=>'required|integer','stock'=>'required|integer']));
        return $request->ajax()? response()->json(['message'=>'بروزرسانی شد']): back();
    }
    public function destroy(Product $product){ $product->delete(); return response()->json(['message'=>'حذف شد']); }
    public function show(Product $product){ return view('admin.products.show', compact('product')); }
}
