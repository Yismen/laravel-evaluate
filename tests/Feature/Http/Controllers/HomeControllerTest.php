<?php

namespace Dainsys\Evaluate\Feature\Http\Controllers;

use Dainsys\Evaluate\Tests\TestCase;
use Dainsys\Evaluate\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_users_cant_view_dashboard()
    {
        $this->actingAs($this->user());

        $response = $this->get(route('evaluate.home'));

        $response->assertRedirect(route('evaluate.my_tickets'));
    }

    /** @test */
    public function agents_are_redirected_to_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAgentUser($department));

        $response = $this->get(route('evaluate.home'));

        $response->assertRedirect(route('evaluate.dashboard'));
    }

    /** @test */
    public function agents_can_view_regular_department_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAgentUser($department));

        $response = $this->get(route('evaluate.dashboard'));

        $response->assertSee('Department Dashboard');
        $response->assertSee('You Are Agent');
        $response->assertDontSee('You Are Admin');
        $response->assertDontSee('Super Admin Dashboard');
    }

    /** @test */
    public function admins_can_view_department_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->departmentAdminUser($department));

        $response = $this->get(route('evaluate.dashboard'));

        $response->assertSee('Department Dashboard');
        $response->assertSee('You Are Admin');
        $response->assertDontSee('You Are Agent');
        $response->assertDontSee('Super Admin Dashboard');
    }

    /** @test */
    public function super_admins_can_view_correct_dashbard()
    {
        $department = Department::factory()->create();
        $this->actingAs($this->evaluateSuperAdminUser());

        $response = $this->get(route('evaluate.dashboard'));

        $response->assertSee('Super Admin Dashboard');
        $response->assertDontSee('You Are Admin');
        $response->assertDontSee('You Are Agent');
    }
}
