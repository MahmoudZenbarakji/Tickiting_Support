<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Notifications\TicketNotification;

class TicketController extends Controller
{
    
    public function index()
    {
        $tickets = Ticket::with('responses')->get();
        return response()->json([
            'success' => true,
            'data' => $tickets,
        ], 200);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:high,medium,low',
        ]);

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'user_id' => auth()->id(),
            'status' => 'open', 
        ]);

        
        $ticket->user->notify(new TicketNotification($ticket, 'open a new ticket'));

        return response()->json([
            'success' => true,
            'message' => 'ticket created successfully',
            'data' => $ticket,
        ], 201);
    }

    
    public function show(Ticket $ticket)
    {
        $ticket->load('responses'); 
        return response()->json([
            'success' => true,
            'data' => $ticket,
        ], 200);
    }

    
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'priority' => 'sometimes|required|in:high,medium,low',
            'status' => 'sometimes|required|in:open,in_progress,closed',
        ]);

        $ticket->update($request->only(['title', 'description', 'priority', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'ticket updated successfully',
            'data' => $ticket,
        ], 200);
    }

    
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التذكرة بنجاح.',
        ], 200);
    }

    // إغلاق التذكرة
    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        event(new TicketStatusUpdated($ticket));
 
        $ticket->user->notify(new TicketNotification($ticket, 'إغلاق التذكرة'));

        return response()->json([
            'success' => true,
            'message' => 'ticket closed',
        ], 200);
    }

    
    public function reopen(Ticket $ticket)
    {
        $ticket->update(['status' => 'open']);
        $ticket->user->notify(new TicketNotification($ticket, 'reopen'));

        return response()->json([
            'success' => true,
            'message' => 'ticket reopen.',
        ], 200);
    }

    
    public function respond(Request $request, Ticket $ticket)
    {
        $request->validate([
            'response_text' => 'required|string',
        ]);

        $ticket->responses()->create([
            'user_id' => auth()->id(),
            'response_text' => $request->response_text,
        ]);
 
        $ticket->user->notify(new TicketNotification($ticket, 'Recieved answerd'));

        return response()->json([
            'success' => true,
            'message' => 'answer added successfully',
        ], 200);
    }
}
