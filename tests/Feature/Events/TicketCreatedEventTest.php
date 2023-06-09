<?php

namespace Dainsys\Evaluate\Tests\Feature\Events;

use Dainsys\Evaluate\Models\Ticket;
use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Evaluate\Models\Department;
use Dainsys\Evaluate\Mail\TicketCreatedMail;
use Dainsys\Evaluate\Events\TicketCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Listeners\SendTicketCreatedMail;

class TicketCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketCreatedEvent::class
        ]);

        $ticket = Ticket::factory()->create();

        Event::assertDispatched(TicketCreatedEvent::class);
        Event::assertListening(
            TicketCreatedEvent::class,
            SendTicketCreatedMail::class
        );
    }

    /** @test */
    public function email_is_sent()
    {
        Mail::fake();
        $superAdmin = $this->evaluateSuperAdminUser();
        $department = Department::factory()->create();
        $department_admin = $this->departmentAdminUser($department);

        $ticket = Ticket::factory()->create(['department_id' => $department->id]);

        Mail::assertQueued(TicketCreatedMail::class);
    }
}
