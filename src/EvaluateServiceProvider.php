<?php

namespace Dainsys\Evaluate;

use Livewire\Livewire;
use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Dainsys\Evaluate\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Scheduling\Schedule;
use Dainsys\Evaluate\Policies\DepartmentPolicy;
use Dainsys\Evaluate\Console\Commands\InstallCommand;
use Dainsys\Evaluate\Console\Commands\CreateSuperUser;
use Dainsys\Evaluate\Console\Commands\UpdateTicketStatus;
use Dainsys\Evaluate\Console\Commands\SendTicketsExpiredReport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class EvaluateServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Department::class => DepartmentPolicy::class
    ];

    public function boot()
    {
        Model::preventLazyLoading(true);
        Paginator::useBootstrap();

        $this->registerPolicies();

        $this->bootEvents();
        $this->bootPublishableAssets();
        $this->bootLoads();
        $this->bootLivewireComponents();
        $this->bootGates();
        $this->boostCommandsAndSchedules();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/evaluate.php',
            'evaluate'
        );
    }

    protected function bootGates()
    {
        Gate::before(function ($user, $ability) {
            $path = str(request()->path());

            if (
                $path->startsWith('evaluate') ||
                $path->beforeLast('::')->endsWith('evaluate')
            ) {
                return $user->isEvaluateSuperAdmin() ? true : null;
            }
        });

        Gate::define('own-ticket', function (User $user, Ticket $ticket) {
            return $ticket->created_by === $user->id;
        });

        Gate::define('grab-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->isDepartmentAdmin($ticket->department) || $user->isDepartmentAgent($ticket->department);
        });

        Gate::define('assign-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->isDepartmentAdmin($ticket->department);
        });

        Gate::define('rate-ticket', function (User $user, Ticket $ticket) {
            return $ticket->created_by === $user->id;
        });

        Gate::define('reopen-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->isDepartmentAdmin($ticket->department) || $ticket->created_by === auth()->user()->id;
        });

        Gate::define('close-ticket', function (User $user, Ticket $ticket) {
            return !$ticket->department
                ? false
                : $user->id === $ticket->created_by || $user->isDepartmentAdmin($ticket->department) || $user->isDepartmentAgent($ticket->department);
        });

        Gate::define('view-dashboards', function (User $user) {
            return $user->hasAnyDepartmentRole() || $user->isEvaluateSuperAdmin()
                ;
        });
    }

    protected function bootPublishableAssets()
    {
        $this->publishes([
            __DIR__ . '/../config/evaluate.php' => config_path('evaluate.php')
        ], 'evaluate:config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dainsys/evaluate')
        ], 'evaluate:views');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/dainsys/evaluate'),
        ], 'evaluate:assets');

        $this->publishes([
            __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/evaluate'),
        ], 'evaluate:translations');
    }

    protected function bootLoads()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'evaluate');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'evaluate');
    }

    protected function boostCommandsAndSchedules()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CreateSuperUser::class,
                UpdateTicketStatus::class,
                SendTicketsExpiredReport::class
            ]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(UpdateTicketStatus::class)
               ->timezone('America/New_York')
               ->everyThirtyMinutes();

            $schedule->command(SendTicketsExpiredReport::class)
                ->timezone('America/New_York')
                ->dailyAt('08');
        });
    }

    protected function bootEvents()
    {
        Event::listen(\Dainsys\Evaluate\Events\ReplyCreatedEvent::class, \Dainsys\Evaluate\Listeners\SendReplyCreatedMail::class);
        Event::listen(\Dainsys\Evaluate\Events\RatingCreatedEvent::class, \Dainsys\Evaluate\Listeners\SendRatingCreatedMail::class);
        Event::listen(\Dainsys\Evaluate\Events\TicketCreatedEvent::class, \Dainsys\Evaluate\Listeners\SendTicketCreatedMail::class);
        Event::listen(\Dainsys\Evaluate\Events\TicketAssignedEvent::class, \Dainsys\Evaluate\Listeners\SendTicketAssignedMail::class);
        Event::listen(\Dainsys\Evaluate\Events\TicketCompletedEvent::class, \Dainsys\Evaluate\Listeners\SendTicketCompletedMail::class);
        Event::listen(\Dainsys\Evaluate\Events\TicketDeletedEvent::class, \Dainsys\Evaluate\Listeners\SendTicketDeletedMail::class);
        Event::listen(\Dainsys\Evaluate\Events\TicketReopenedEvent::class, \Dainsys\Evaluate\Listeners\SendTicketReopenedMail::class);

        Event::listen(\Illuminate\Mail\Events\MessageSent::class, \Dainsys\Evaluate\Listeners\RemoveMailAttachment::class);
    }

    protected function bootLivewireComponents()
    {
        Livewire::component('evaluate::dashboard', \Dainsys\Evaluate\Http\Livewire\Admin\Dashboard::class);

        Livewire::component('evaluate::department.table', \Dainsys\Evaluate\Http\Livewire\Department\Table::class);
        Livewire::component('evaluate::department.index', \Dainsys\Evaluate\Http\Livewire\Department\Index::class);
        Livewire::component('evaluate::department.detail', \Dainsys\Evaluate\Http\Livewire\Department\Detail::class);
        Livewire::component('evaluate::department.form', \Dainsys\Evaluate\Http\Livewire\Department\Form::class);

        Livewire::component('evaluate::subject.table', \Dainsys\Evaluate\Http\Livewire\Subject\Table::class);
        Livewire::component('evaluate::subject.index', \Dainsys\Evaluate\Http\Livewire\Subject\Index::class);
        Livewire::component('evaluate::subject.detail', \Dainsys\Evaluate\Http\Livewire\Subject\Detail::class);
        Livewire::component('evaluate::subject.form', \Dainsys\Evaluate\Http\Livewire\Subject\Form::class);

        Livewire::component('evaluate::evaluate_super_admin.index', \Dainsys\Evaluate\Http\Livewire\EvaluateSuperAdmin\Index::class);

        Livewire::component('evaluate::department_role.index', \Dainsys\Evaluate\Http\Livewire\DepartmentRole\Index::class);
        Livewire::component('evaluate::department_role.table', \Dainsys\Evaluate\Http\Livewire\DepartmentRole\Table::class);
        Livewire::component('evaluate::department_role.form', \Dainsys\Evaluate\Http\Livewire\DepartmentRole\Form::class);

        Livewire::component('evaluate::ticket.table', \Dainsys\Evaluate\Http\Livewire\Ticket\Table::class);
        Livewire::component('evaluate::ticket.index', \Dainsys\Evaluate\Http\Livewire\Ticket\Index::class);
        Livewire::component('evaluate::ticket.detail', \Dainsys\Evaluate\Http\Livewire\Ticket\Detail::class);
        Livewire::component('evaluate::ticket.form', \Dainsys\Evaluate\Http\Livewire\Ticket\Form::class);

        Livewire::component('evaluate::dashboard.index', \Dainsys\Evaluate\Http\Livewire\Dashboard\Index::class);
        Livewire::component('evaluate::dashboard.table', \Dainsys\Evaluate\Http\Livewire\Dashboard\Table::class);

        Livewire::component('evaluate::ticket.close', \Dainsys\Evaluate\Http\Livewire\Ticket\CloseTicket::class);
        Livewire::component('evaluate::ticket.rating', \Dainsys\Evaluate\Http\Livewire\Ticket\RateTicket::class);

        Livewire::component('evaluate::reply.form', \Dainsys\Evaluate\Http\Livewire\Reply\Form::class);

        Livewire::component('evaluate::charts.weekly-tickets-count', \Dainsys\Evaluate\Http\Livewire\Charts\WeeklyTicketsCountChart::class);
        Livewire::component('evaluate::charts.weekly-tickets-count-by-subject', \Dainsys\Evaluate\Http\Livewire\Charts\WeeklyTicketsCountBySubjectChart::class);
        Livewire::component('evaluate::charts.weekly-tickets-completion-rate', \Dainsys\Evaluate\Http\Livewire\Charts\WeeklyTicketsCompletionRateChart::class);
        Livewire::component('evaluate::charts.weekly-tickets-compliance-rate', \Dainsys\Evaluate\Http\Livewire\Charts\WeeklyTicketsComplianceRateChart::class);
        Livewire::component('evaluate::charts.weekly-tickets-satisfaction-rate', \Dainsys\Evaluate\Http\Livewire\Charts\WeeklyTicketsSatisfactionRateChart::class);
    }
}
