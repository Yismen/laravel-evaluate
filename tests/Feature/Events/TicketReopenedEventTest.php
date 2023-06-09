<?php

namespace Dainsys\Evaluate\Tests\Feature\Events;

use Dainsys\Evaluate\Models\Ticket;
use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Evaluate\Mail\TicketReopenedMail;
use Dainsys\Evaluate\Events\TicketReopenedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Listeners\SendTicketReopenedMail;

class TicketReopenedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketReopenedEvent::class
        ]);

        $this->evaluateSuperAdminUser();
        $ticket = Ticket::factory()->create();

        $ticket->reopen();

        Event::assertDispatched(TicketReopenedEvent::class);
        Event::assertListening(
            TicketReopenedEvent::class,
            SendTicketReopenedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $this->evaluateSuperAdminUser();
        $ticket = Ticket::factory()->assigned()->create();
        $ticket->reopen();

        Mail::assertQueued(TicketReopenedMail::class);
    }
}
