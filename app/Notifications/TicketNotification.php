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
        $subject = "Notification about a ticket: {$this->ticket->title}";
        
        return (new MailMessage)
            ->subject($subject)
            ->line("ticket address '{$this->ticket->title}' Done{$this->action}.")
            ->action('Display Ticket', url("/tickets/{$this->ticket->id}"))
            ->line('thank you');
    }
}
