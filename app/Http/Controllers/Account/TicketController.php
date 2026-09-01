<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        return view('account.tickets', [
            'tickets' => $request->user()->tickets()->withCount('messages')->latest()->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        return view('account.ticket-create', [
            'orders' => $request->user()->orders()->latest()->limit(20)->get(),
        ]);
    }

    public function show(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        return view('account.ticket-show', [
            'ticket' => $ticket->load('messages.user', 'order'),
        ]);
    }
}
