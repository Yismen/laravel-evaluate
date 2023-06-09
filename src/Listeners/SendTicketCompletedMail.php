<?php

namespace Dainsys\Evaluate\Listeners;

use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Evaluate\Mail\TicketCompletedMail;
use Dainsys\Evaluate\Services\RecipientsService;
use Dainsys\Evaluate\Events\TicketCompletedEvent;

class SendTicketCompletedMail
{
    protected Ticket $ticket;
    protected string $comment;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketCompletedEvent $event)
    {
        $this->ticket = $event->ticket;
        $this->comment = $event->comment;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCompletedMail($this->ticket, $this->comment));
        }
    }

    protected function recipients(): Collection
    {
        return $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->departmentAdmins()
            ->get();
    }
}
