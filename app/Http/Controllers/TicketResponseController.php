<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Http\Request;

class TicketResponseController extends Controller
{
    public function index(Ticket $ticket)
    {
        $responses = $ticket->responses()->get();
        return response()->json([
            'success' => true,
            'data' => $responses,
        ], 200);
    }

    
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'response_text' => 'required|string',
        ]);

        $response = TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'response_text' => $request->response_text,
        ]);

        
        if (auth()->user()->role === 'Support Agent') {
            $ticket->update(['status' => 'in_progress']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Active response added.',
            'data' => $response,
        ], 201);
    }

    public function show(Ticket $ticket, TicketResponse $response)
    {
        return response()->json([
            'success' => true,
            'data' => $response,
        ], 200);
    }

    
    public function update(Request $request, Ticket $ticket, TicketResponse $response)
    {
        $request->validate([
            'response_text' => 'required|string',
        ]);

        $response->update([
            'response_text' => $request->response_text,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'answerd added successfully',
            'data' => $response,
        ], 200);
    }

    public function destroy(Ticket $ticket, TicketResponse $response)
    {
        $response->delete();

        return response()->json([
            'success' => true,
            'message' => 'answer was deleted successfully',
        ], 200);
    }
}
