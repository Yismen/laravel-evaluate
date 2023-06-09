<?php

namespace Dainsys\Evaluate\Tests\Feature\Events;

use Dainsys\Evaluate\Models\Ticket;
use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Evaluate\Mail\TicketDeletedMail;
use Dainsys\Evaluate\Events\TicketDeletedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Listeners\SendTicketDeletedMail;

class TicketDeletedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketDeletedEvent::class
        ]);
        $this->evaluateSuperAdminUser();

        $ticket = Ticket::factory()->create();

        $ticket->delete();

        Event::assertDispatched(TicketDeletedEvent::class);
        Event::assertListening(
            TicketDeletedEvent::class,
            SendTicketDeletedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $ticket = Ticket::factory()->create();
        $ticket->delete();

        Mail::assertQueued(TicketDeletedMail::class);
    }
}
