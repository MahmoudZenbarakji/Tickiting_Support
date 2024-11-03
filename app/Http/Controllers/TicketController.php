<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Notifications\TicketNotification;

class TicketController extends Controller
{
    // عرض جميع التذاكر
    public function index()
    {
        $tickets = Ticket::with('responses')->get();
        return response()->json([
            'success' => true,
            'data' => $tickets,
        ], 200);
    }

    // إنشاء تذكرة جديدة
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
            'status' => 'open', // تعيين الحالة الافتراضية كـ "مفتوحة"
        ]);

        // إرسال إشعار عند فتح تذكرة جديدة
        $ticket->user->notify(new TicketNotification($ticket, 'فتح تذكرة جديدة'));

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء التذكرة بنجاح.',
            'data' => $ticket,
        ], 201);
    }

    // عرض تذكرة محددة
    public function show(Ticket $ticket)
    {
        $ticket->load('responses'); // جلب الردود المرتبطة بالتذكرة
        return response()->json([
            'success' => true,
            'data' => $ticket,
        ], 200);
    }

    // تحديث تذكرة معينة
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
            'message' => 'تم تحديث التذكرة بنجاح.',
            'data' => $ticket,
        ], 200);
    }

    // حذف تذكرة معينة
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

        // إرسال إشعار عند إغلاق التذكرة
        $ticket->user->notify(new TicketNotification($ticket, 'إغلاق التذكرة'));

        return response()->json([
            'success' => true,
            'message' => 'تم إغلاق التذكرة بنجاح.',
        ], 200);
    }

    // إعادة فتح التذكرة
    public function reopen(Ticket $ticket)
    {
        $ticket->update(['status' => 'open']);

        // إرسال إشعار عند إعادة فتح التذكرة
        $ticket->user->notify(new TicketNotification($ticket, 'إعادة فتح التذكرة'));

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة فتح التذكرة بنجاح.',
        ], 200);
    }

    // إضافة رد على تذكرة
    public function respond(Request $request, Ticket $ticket)
    {
        $request->validate([
            'response_text' => 'required|string',
        ]);

        $ticket->responses()->create([
            'user_id' => auth()->id(),
            'response_text' => $request->response_text,
        ]);

        // إرسال إشعار عند تلقي رد على التذكرة
        $ticket->user->notify(new TicketNotification($ticket, 'تلقي رد على التذكرة'));

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الرد بنجاح.',
        ], 200);
    }
}
