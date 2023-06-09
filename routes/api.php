<?php

use Dainsys\Evaluate\Models\Form;
use Dainsys\Evaluate\Models\Entry;
use Dainsys\Evaluate\Models\Response;
use Illuminate\Support\Facades\Route;
use Dainsys\Evaluate\Http\Resources\FormResource;
use Dainsys\Evaluate\Http\Resources\EntryResource;

Route::middleware(['api'])->group(function () {
    // Auth Routes
    Route::as('evaluate.api.')
        ->prefix('evaluate/api')
        ->middleware(
            preg_split('/[,|]+/', config('evaluate.midlewares.api'), -1, PREG_SPLIT_NO_EMPTY)
        )->group(function () {
            // Route::get('form/{form}', function ($form) {
            //     return new FormResource(Form::with('responses')->find($form));
            // })->name('form.show');
            // Route::get('entries/{entry}', function ($entry) {
            //     return new EntryResource(Entry::with('form', 'responses')->findOrFail($entry));
            // })->name('entries.show');
            // Route::get('responses/entry/{entry}', ['data' => 'response by entry'])->name('responses.entry.show');
        });
});
