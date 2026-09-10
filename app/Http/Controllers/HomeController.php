<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    public function index(){
        $featured = Product::where('is_featured', true)->latest()->take(12)->get();
        $bestsellers = Product::orderBy('views','desc')->take(8)->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $banners = Banner::where('is_active', true)->get();
        // For static rendering, these will be passed to view; AJAX will hydrate
        return view('front.home', compact('featured','bestsellers','categories','banners'));
    }
}
