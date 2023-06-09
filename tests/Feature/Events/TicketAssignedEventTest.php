<?php

namespace Dainsys\Evaluate\Tests\Feature\Events;

use Dainsys\Evaluate\Models\Ticket;
use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Evaluate\Mail\TicketAssignedMail;
use Dainsys\Evaluate\Events\TicketAssignedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Listeners\SendTicketAssignedMail;

class TicketAssignedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketAssignedEvent::class
        ]);

        $ticket = Ticket::factory()->create();

        $this->evaluateSuperAdminUser();

        $ticket->assignTo($this->departmentAgentUser($ticket->department));

        Event::assertDispatched(TicketAssignedEvent::class);
        Event::assertListening(
            TicketAssignedEvent::class,
            SendTicketAssignedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $ticket = Ticket::factory()->create();
        $ticket->assignTo($this->departmentAgentUser($ticket->department));

        Mail::assertQueued(TicketAssignedMail::class);
    }
}
