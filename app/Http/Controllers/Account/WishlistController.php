<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        return view('account.wishlist', [
            'products' => $request->user()->wishedProducts()
                ->with(['brand', 'images'])
                ->orderByPivot('created_at', 'desc')
                ->paginate(12),
        ]);
    }
}
