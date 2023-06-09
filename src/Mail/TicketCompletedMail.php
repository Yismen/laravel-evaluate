<?php

namespace Dainsys\Evaluate\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Ticket $ticket;

    public $comment;

    public function __construct(Ticket $ticket, string $comment = '')
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
    }

    public function build()
    {
        return $this
            ->subject("Ticket #{$this->ticket->reference} Completed")
            ->priority($this->ticket->mail_priority)
            ->markdown('evaluate::mail.ticket-completed', [
                'user' => $this->ticket->audits()->latest()->first()?->user
            ])

        ;
    }
}
