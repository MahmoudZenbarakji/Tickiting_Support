<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\TicketStatusUpdated;
use App\Notifications\TicketNotification;
class NotifyUserAboutTicketStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketStatusUpdated $event)
    {
        $event->ticket->user->notify(new TicketNotification($event->ticket, 'تغيير حالة التذكرة'));
    }
}
