<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function security(Request $request)
    {
        return view('account.security', [
            'user' => $request->user(),
            'sessions' => DB::table('sessions')
                ->where('user_id', $request->user()->id)
                ->orderByDesc('last_activity')
                ->limit(10)->get(),
        ]);
    }

    public function notifications(Request $request)
    {
        return view('account.notifications', [
            'notifications' => $request->user()->notifications()->paginate(15),
        ]);
    }

    public function payments(Request $request)
    {
        return view('account.payments', [
            'orders' => $request->user()->orders()->paid()->latest('paid_at')->paginate(12),
            'totalPaid' => (int) $request->user()->orders()->paid()->sum('grand_total'),
        ]);
    }

    public function reviews(Request $request)
    {
        return view('account.reviews', [
            'reviews' => $request->user()->reviews()->with('product.images')->latest()->paginate(10),
        ]);
    }
}
