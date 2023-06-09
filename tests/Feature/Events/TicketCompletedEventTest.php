<?php

namespace Dainsys\Evaluate\Tests\Feature\Events;

use Dainsys\Evaluate\Models\Ticket;
use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Evaluate\Mail\TicketCompletedMail;
use Dainsys\Evaluate\Events\TicketCompletedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Listeners\SendTicketCompletedMail;

class TicketCompletedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketCompletedEvent::class
        ]);

        $this->evaluateSuperAdminUser();
        $ticket = Ticket::factory()->create();

        $ticket->complete();

        Event::assertDispatched(TicketCompletedEvent::class);
        Event::assertListening(
            TicketCompletedEvent::class,
            SendTicketCompletedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $this->evaluateSuperAdminUser();
        $ticket = Ticket::factory()->assigned()->create();
        $ticket->complete();

        Mail::assertQueued(TicketCompletedMail::class);
    }
}
