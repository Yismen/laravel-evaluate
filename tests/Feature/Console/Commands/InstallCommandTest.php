<?php

namespace Dainsys\Evaluate\Tests\Feature\Console\Commands;

use Dainsys\Evaluate\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Evaluate\Console\Commands\InstallCommand;

class InstallCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function install_command_creates_files()
    {
        $this->artisan(InstallCommand::class)
            ->expectsConfirmation('Would you like to publish the evaluate\'s configuration file?', 'yes')
            ->assertSuccessful();

        $this->assertFileExists(config_path('evaluate.php'));
        $this->assertFileExists(resource_path('views/vendor/dainsys/evaluate/layouts/app.blade.php'));
    }
}
