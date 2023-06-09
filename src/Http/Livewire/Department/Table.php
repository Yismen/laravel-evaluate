<?php

namespace Dainsys\Evaluate\Http\Livewire\Department;

use Dainsys\Evaluate\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Dainsys\Evaluate\Http\Livewire\AbstractDataTableComponent;

class Table extends AbstractDataTableComponent
{
    protected string $module = 'Department';

    protected $listeners = [
        'departmentUpdated' => '$refresh',
        'informationUpdated' => '$refresh',
    ];

    public function builder(): Builder
    {
        return Department::query()
            // ->withCount([
            //     'departments',
            // ])
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->sortable()
                ->searchable(),
            Column::make('Ticket Prefix')
                ->sortable()
                ->searchable(),
            Column::make('Description')
                ->sortable()
                ->searchable(),
            // Column::make('Departments', 'id')
            //     ->format(fn ($value, $row) => view('evaluate::tables.badge')->with(['value' => $row->departments_count])),
            Column::make('Actions', 'id')
                ->view('evaluate::tables.actions'),
        ];
    }
}
