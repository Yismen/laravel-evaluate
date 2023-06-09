<?php

namespace Dainsys\Evaluate\Listeners;

use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Evaluate\Mail\TicketCreatedMail;
use Dainsys\Evaluate\Events\TicketCreatedEvent;
use Dainsys\Evaluate\Services\RecipientsService;

class SendTicketCreatedMail
{
    protected Ticket $ticket;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketCreatedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCreatedMail($this->ticket));
        }
    }

    protected function recipients()
    {
        return $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->departmentAdmins()
            ->departmentAgents()
            ->get();
    }
}
