<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;

class AdminController extends Controller
{
    // عرض بيانات لوحة التحكم كـ JSON
    public function dashboard()
    {
        $openTickets = Ticket::where('status', 'open')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();
        $totalTickets = Ticket::count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'open_tickets' => $openTickets,
                'closed_tickets' => $closedTickets,
                'total_tickets' => $totalTickets,
            ],
        ], 200);
    }

    // عرض إحصائيات التذاكر كـ JSON
    public function statistics()
    {
        $openTickets = Ticket::where('status', 'open')->count();
        $inProgressTickets = Ticket::where('status', 'in_progress')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'open_tickets' => $openTickets,
                'in_progress_tickets' => $inProgressTickets,
                'closed_tickets' => $closedTickets,
            ],
        ], 200);
    }
}
