<?php

namespace App\Http\Controllers;

use App\Models\SearchLog;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected SearchService $search) {}

    public function index(Request $request)
    {
        $term = trim((string) $request->input('q'));
        $products = $this->search->paginate($request);

        if ($term !== '' && $request->page === null) {
            $this->search->log($term, $products->total());
        }

        return view('pages.search', [
            'term' => $term,
            'products' => $products,
            'facets' => $this->search->facets($request),
            'category' => null,
            'trending' => SearchLog::trending(),
            'title' => $term !== '' ? 'نتایج جستجو برای «'.$term.'»' : 'جستجو در دیجی‌نو',
            'breadcrumbs' => [['label' => 'جستجو', 'url' => route('search')]],
        ]);
    }
}
