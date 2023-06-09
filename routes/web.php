<?php

use Illuminate\Support\Facades\Route;
use Dainsys\Evaluate\Http\Controllers\HomeController;

Route::middleware(config('evaluate.middlewares.web', ['web', 'auth']))
    ->group(function () {
        Route::as('evaluate.')
            ->prefix('evaluate')
            ->group(function () {
                // Route::get('', HomeController::class)->name('home');
                // Route::get('admin', HomeController::class)->name('admin');
                // Route::get('my_tickets', \Dainsys\Evaluate\Http\Livewire\Ticket\Index::class)->name('my_tickets');
                // Route::get('dashboard', \Dainsys\Evaluate\Http\Livewire\Dashboard\Index::class)->name('dashboard');

                // Route::as('admin.')
                //     ->group(function () {
                //         Route::get('departments', \Dainsys\Evaluate\Http\Livewire\Department\Index::class)->name('departments.index');

                //         Route::get('subjects', \Dainsys\Evaluate\Http\Livewire\Subject\Index::class)->name('subjects.index');

                //         Route::get('evaluate_super_admins', \Dainsys\Evaluate\Http\Livewire\EvaluateSuperAdmin\Index::class)->name('evaluate_super_admins.index');

                //         Route::get('department_roles', \Dainsys\Evaluate\Http\Livewire\DepartmentRole\Index::class)->name('department_roles.index');
                //     });
            });
    });
