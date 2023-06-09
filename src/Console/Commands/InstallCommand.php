<?php

namespace Dainsys\Evaluate\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluate:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Dainsys Evaluate';

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
        $this->call('vendor:publish', ['--tag' => 'evaluate:assets', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'evaluate:views']);
        $this->call('vendor:publish', ['--tag' => 'livewire-charts:public']);
        $this->call('migrate');

        if ($this->confirm('Would you like to publish the evaluate\'s configuration file?')) {
            $this->call('vendor:publish', ['--tag' => 'evaluate:config']);
        }

        $this->info('All done!');

        return 0;
    }
}
