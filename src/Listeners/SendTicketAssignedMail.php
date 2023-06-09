<?php

namespace Dainsys\Evaluate\Listeners;

use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Evaluate\Mail\TicketAssignedMail;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Evaluate\Events\TicketAssignedEvent;
use Dainsys\Evaluate\Services\RecipientsService;

class SendTicketAssignedMail
{
    protected Ticket $ticket;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketAssignedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketAssignedMail($this->ticket));
        }
    }

    protected function recipients(): Collection
    {
        return  $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->departmentAdmins()
            ->get();
    }
}
