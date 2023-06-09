<?php

namespace Dainsys\Evaluate\Feature\Http\Livewire\EvaluateSuperAdmin;

use Livewire\Livewire;
use Dainsys\Evaluate\Tests\TestCase;
use Dainsys\Evaluate\Models\EvaluateSuperAdmin;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Http\Livewire\EvaluateSuperAdmin\Index;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function evaluate_super_admin_index_component_requires_authorization()
    {
        $this->actingAs($this->user());

        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function evaluate_super_admins_index_route_requires_authorization()
    {
        $component = Livewire::test(Index::class);

        $component->assertForbidden();
    }

    /** @test */
    public function evaluate_super_admins_index_works_for_authorized_users_and_renders_correct_view()
    {
        $this->actingAs($this->evaluateSuperAdminUser());

        $component = Livewire::test(Index::class);

        $component->assertOk();
        $component->assertViewIs('evaluate::livewire.evaluate_super_admin.index');
    }

    /** @test */
    public function evaluate_super_admins_index_works_for_evaluate_super_admin_users_and_renders_correct_view()
    {
        $this->actingAs($this->evaluateSuperAdminUser());
        $users = UserFactory::new()->count(2)->create();

        $component = Livewire::test(Index::class);

        $component->assertViewHas('users');
        $component->assertOk();
    }

    /** @test */
    public function evaluate_super_admins_index_shows_all_users_except_authenticated_user()
    {
        $this->actingAs($this->evaluateSuperAdminUser());
        $user = UserFactory::new()->create();

        $component = Livewire::test(Index::class);

        $component->assertSee($user->name);
        $component->assertDontSee(auth()->user()->name);
    }

    /** @test */
    // public function evaluate_super_admins_index_add_super_users()
    // {
    //     $this->actingAs($this->evaluateSuperAdminUser());
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'evaluate_super_admins' => EvaluateSuperAdmin::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('evaluate_super_admins', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(EvaluateSuperAdmin::class, ['user_id' => $regular_user->id]);
    // }

    /** @test */
    // public function evaluate_super_admins_index_removes_super_users()
    // {
    //     $this->actingAs($this->evaluateSuperAdminUser());
    //     $regular_user = UserFactory::new()->create();

    //     $component = Livewire::test(Index::class, [
    //         'evaluate_super_admins' => EvaluateSuperAdmin::pluck('user_id')->values()->toArray()
    //     ]);
    //     $component->set('evaluate_super_admins', [0 => $regular_user->id]);

    //     $this->assertDatabaseHas(EvaluateSuperAdmin::class, ['user_id' => $regular_user->id]);
    // }
}
