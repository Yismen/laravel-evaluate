<?php

namespace Dainsys\Evaluate\Feature\Http\Livewire\EvaluateSuperAdmin;

use Livewire\Livewire;
use Dainsys\Evaluate\Tests\TestCase;
use Dainsys\Evaluate\Models\Department;
use Dainsys\Evaluate\Http\Livewire\Dashboard\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dashboard_component_renders_correctly_for_regular_users()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertRedirect(route('evaluate.my_tickets'));
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_agent()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAgentUser($department));

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_department_admin()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAdminUser($department));

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }

    /** @test */
    public function dashboard_component_renders_correctly_for_evaluate_super_admin()
    {
        $this->actingAs($this->evaluateSuperAdminUser());

        $component = Livewire::test(Index::class);

        $component->assertOk();
    }
}
