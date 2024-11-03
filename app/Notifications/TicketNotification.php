<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $ticket;
    private $action;

    public function __construct(Ticket $ticket, $action)
    {
        $this->ticket = $ticket;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = "إشعار حول تذكرتك: {$this->ticket->title}";
        
        return (new MailMessage)
            ->subject($subject)
            ->line("تذكرتك بعنوان '{$this->ticket->title}' قد تم {$this->action}.")
            ->action('عرض التذكرة', url("/tickets/{$this->ticket->id}"))
            ->line('شكراً لك!');
    }
}
