<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.tickets.index', [
            'tickets' => Ticket::with('user')->withCount('messages')
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
                ->when($request->filled('department'), fn ($q) => $q->where('department', $request->input('department')))
                ->latest()->paginate(config('digino.admin.per_page'))->withQueryString(),
            'counts' => [
                'all' => Ticket::count(),
                'open' => Ticket::where('status', 'open')->count(),
                'answered' => Ticket::where('status', 'answered')->count(),
                'closed' => Ticket::where('status', 'closed')->count(),
            ],
        ]);
    }

    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', [
            'ticket' => $ticket->load(['messages.user', 'user', 'order']),
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:3000'],
        ], ['body.required' => 'متن پاسخ را بنویسید.']);

        $message = $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'is_staff' => true,
        ]);

        $ticket->update(['status' => 'answered']);

        return $this->ok('پاسخ ارسال شد.', [
            'html' => view('admin.tickets.partials.message', [
                'message' => $message->load('user'),
            ])->render(),
        ]);
    }

    public function status(Request $request, Ticket $ticket)
    {
        $data = $request->validate(['status' => ['required', 'in:open,answered,closed']]);

        $ticket->update($data);

        return $this->ok('وضعیت تیکت تغییر کرد.', ['status' => $ticket->status_label]);
    }
}
