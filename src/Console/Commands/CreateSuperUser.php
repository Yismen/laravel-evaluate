<?php

namespace Dainsys\Evaluate\Console\Commands;

use Illuminate\Console\Command;

class CreateSuperUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluate:create-super-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert a current user into a evaluate super user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('Please provide the email of the user to be made evaluate super admin!');
        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            $this->warn('No user found with this email. Please provide a valid user');

            return self::FAILURE;
        }

        if (!$user->isEvaluateSuperAdmin()) {
            $user->evaluateSuperAdmin()->create();
        }

        $this->info("User {$user->name} is now a Evaluate Super Admin user!");

        return self::SUCCESS;
    }
}
