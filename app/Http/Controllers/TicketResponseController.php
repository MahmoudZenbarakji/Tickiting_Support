<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Http\Request;

class TicketResponseController extends Controller
{
    // عرض جميع الردود لتذكرة معينة
    public function index(Ticket $ticket)
    {
        $responses = $ticket->responses()->get();
        return response()->json([
            'success' => true,
            'data' => $responses,
        ], 200);
    }

    // إضافة رد إلى تذكرة
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

        // تحديث حالة التذكرة بناءً على الردود
        if (auth()->user()->role === 'Support Agent') {
            $ticket->update(['status' => 'in_progress']);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الرد بنجاح.',
            'data' => $response,
        ], 201);
    }

    // عرض رد معين بناءً على معرفه
    public function show(Ticket $ticket, TicketResponse $response)
    {
        return response()->json([
            'success' => true,
            'data' => $response,
        ], 200);
    }

    // تحديث رد معين
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
            'message' => 'تم تحديث الرد بنجاح.',
            'data' => $response,
        ], 200);
    }

    // حذف رد معين
    public function destroy(Ticket $ticket, TicketResponse $response)
    {
        $response->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الرد بنجاح.',
        ], 200);
    }
}
