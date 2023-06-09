<?php

namespace Dainsys\Evaluate\Feature\Http\Livewire\Subject;

use Livewire\Livewire;
use Dainsys\Evaluate\Models\Subject;
use Dainsys\Evaluate\Tests\TestCase;
use Dainsys\Evaluate\Http\Livewire\Subject\Detail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function subject_detail_requires_authorization()
    {
        $subject = Subject::factory()->create();
        $component = Livewire::test(Detail::class);

        $component->emit('showSubject', $subject);

        $component->assertForbidden();
    }

    /** @test */
    public function subject_detail_component_grants_access_to_evaluate_super_admin()
    {
        $this->actingAs($this->evaluateSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showSubject', $subject);

        $component->assertOk();
    }

    /** @test */
    public function subject_detail_component_grants_access_to_authorized_users()
    {
        $this->actingAs($this->evaluateSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showSubject', $subject);

        $component->assertOk();
    }

    /** @test */
    public function subject_detail_component_responds_to_wants_show_subject_event()
    {
        $this->actingAs($this->evaluateSuperAdminUser());
        $subject = Subject::factory()->create();

        $component = Livewire::test(Detail::class);
        $component->emit('showSubject', $subject);

        $component->assertSet('editing', false);
        $component->assertDispatchedBrowserEvent('closeAllModals');
        $component->assertDispatchedBrowserEvent('showSubjectDetailModal');
    }
}
