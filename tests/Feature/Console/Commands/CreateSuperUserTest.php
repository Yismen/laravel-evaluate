<?php

namespace Dainsys\Evaluate\Tests\Feature\Console\Commands;

use Dainsys\Evaluate\Tests\TestCase;
use Dainsys\Evaluate\Models\EvaluateSuperAdmin;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Console\Commands\CreateSuperUser;

class CreateSuperUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_super_user_throws_exception_if_invalid_user_email_passed()
    {
        $this->artisan(CreateSuperUser::class)
            ->expectsQuestion('Please provide the email of the user to be made evaluate super admin!', 'invalid user')
            ->assertFailed();
    }

    /** @test */
    public function create_super_user_creates_super_user()
    {
        $user = UserFactory::new()->create();

        $this->artisan(CreateSuperUser::class)
            ->expectsQuestion('Please provide the email of the user to be made evaluate super admin!', $user->email)
            ->assertSuccessful();

        $this->assertDatabaseHas(EvaluateSuperAdmin::class, ['user_id' => $user->id]);
    }
}
