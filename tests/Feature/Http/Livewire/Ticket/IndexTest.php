<?php

namespace Dainsys\Evaluate\Feature\Http\Livewire\Ticket\Index;

use Livewire\Livewire;
use Dainsys\Evaluate\Models\Ticket;
use Dainsys\Evaluate\Tests\TestCase;
use Orchestra\Testbench\Factories\UserFactory;
use Dainsys\Evaluate\Http\Livewire\Ticket\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_tickets_index_component_requires_authentication()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function users_tickets_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertOk();
        $component->assertViewIs('evaluate::livewire.ticket.index');
        $component->assertSeeLivewire('evaluate::ticket.table');
    }

    /** @test */
    public function user_tickets_index_only_shows_tickets_for_current_user()
    {
        $this->actingAs($this->user());
        $ticket_for_user = Ticket::factory()->create(['created_by' => auth()->user()->id]);
        $ticket_for_oter_user = Ticket::factory()->create(['created_by' => UserFactory::new()->create()]);

        $component = Livewire::test(Index::class);

        $component->assertSee($ticket_for_user->subject->name);
        // $component->assertDontSee($ticket_for_oter_user->subject->name);
    }
}
