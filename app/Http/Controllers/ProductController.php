<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    public function index(Request $request){
        $query = Product::query()->where('is_active', true);
        if($request->filled('category')) $query->whereHas('category', fn($q)=>$q->where('slug', $request->category));
        if($request->filled('brand')) $query->where('brand_id', $request->brand);
        if($request->filled('q')) $query->where('name','like','%'.$request->q.'%');
        if($request->filled('min_price')) $query->where('price','>=',$request->min_price);
        if($request->filled('max_price')) $query->where('price','<=',$request->max_price);
        if($request->filled('sort')){
            match($request->sort){
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest' => $query->latest(),
                'popular' => $query->orderByDesc('views'),
                default => $query->latest()
            };
        } else $query->latest();
        $products = $query->paginate(20)->withQueryString();
        if($request->ajax()) return response()->json(['products' => $products]);
        return view('front.shop', compact('products'));
    }
    public function show($slug){
        $product = Product::where('slug',$slug)->firstOrFail();
        $product->increment('views');
        $related = Product::where('category_id',$product->category_id)->where('id','!=',$product->id)->take(6)->get();
        if(request()->ajax()) return response()->json(['product'=>$product,'related'=>$related]);
        return view('front.product', compact('product','related'));
    }
    public function search(Request $request){
        $q = $request->q;
        $products = Product::where('name','like',"%{$q}%")->paginate(20);
        return view('front.search', compact('products','q'));
    }
}
