<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\SearchService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected SearchService $search) {}

    public function index()
    {
        return view('pages.categories', [
            'categories' => Category::active()->roots()
                ->with(['children' => fn ($q) => $q->active()->withCount('products')])
                ->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Request $request, Category $category)
    {
        abort_unless($category->is_active, 404);

        $category->load(['children' => fn ($q) => $q->active()->orderBy('sort_order'), 'parent']);

        $breadcrumbs = collect($category->breadcrumbTrail())
            ->map(fn (Category $c) => ['label' => $c->name, 'url' => route('categories.show', $c->slug)])
            ->all();

        return view('pages.category', [
            'category' => $category,
            'title' => $category->name,
            'subtitle' => $category->description,
            'products' => $this->search->paginate($request, $category),
            'facets' => $this->search->facets($request, $category),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
