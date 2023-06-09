<?php

namespace Dainsys\Evaluate\Http\Livewire\Dashboard;

use App\Models\User;
use Dainsys\Evaluate\Models\Ticket;
use Dainsys\Evaluate\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Evaluate\Services\SubjectService;
use Dainsys\Evaluate\Enums\DepartmentRolesEnum;
use Dainsys\Evaluate\Enums\TicketPrioritiesEnum;
use Dainsys\Evaluate\Services\DepartmentService;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Dainsys\Evaluate\Services\UserDepartmentRoleService;
use Dainsys\Evaluate\Http\Livewire\AbstractDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class Table extends AbstractDataTableComponent
{
    protected string $module = 'Ticket';
    protected $create_button = false;
    protected $edit_button = false;
    public $department;
    protected $agent_id;

    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'dashboardUpdated' => '$refresh',
    ];

    public function mount(Department $department)
    {
        $this->department = $department;
        $this->table['filters']['completion_status'] = 'incompleted';
    }

    public function builder(): Builder
    {
        return Ticket::query()
            ->addSelect([
                'created_by',
                'subject_id',
                'assigned_to',
                'assigned_at',
                'expected_at',
                'priority',
                'completed_at',
            ])
            ->with([
                'department',
                'subject',
                'agent',
                'owner',
                // 'replies'
            ])
            ->when(
                auth()->user()->departmentRole?->role === DepartmentRolesEnum::Agent,
                function ($query) {
                    $query->incompleted();
                }
            )
            ->when($this->department->id, function ($query) {
                $query->whereHas('department', function ($query) {
                    $query->where('id', $this->department->id);
                });
            })
            // ->withCount([
            //     'tickets',
            // ])
            ;
    }

    // public function bulkActions(): array
    // {
    //     parent::bulkActions();

    //     if (auth()->user()->isDepartmentAdmin($this->department)) {
    //         return [
    //             'assignMany' => 'Assign'
    //         ];
    //     }
    // }

    // public function assignMany()
    // {
    //     $records = Ticket::findMany($this->getSelected());

    //     $this->clearSelected();
    // }

    public function columns(): array
    {
        return [

            Column::make('Reference')
                ->format(fn ($value) => '#' . $value)
                ->sortable()
                ->searchable(),
            Column::make('Department', 'department.name')
                ->hideIf(!is_null($this->department->id))
                ->sortable()
                ->searchable(),
            Column::make('Subject', 'subject.name')
                ->sortable()
                ->searchable(),
            // Column::make('Description')
            //     ->format(fn ($value, $row) => $row->short_description)
            //     ->html()
            //     ->sortable()
            //     ->searchable(),
            Column::make('Owner', 'owner.name')
                ->sortable()
                ->searchable(),
            Column::make('Priority', 'subject.priority')
                ->format(fn ($value, $row) => "<span class='{$row->subject?->priority->class()}'> {$row->subject?->priority->name}</span>")
                ->html()
                ->searchable()
                ->sortable(),
            Column::make('Status')
                ->format(fn ($value, $row) => "<span class='{$row->status->class()}'> " . str($row->status->name)->headline() . '</span>')
                ->html()
                ->sortable()
                ->searchable(),
            Column::make('Expected At')
                ->format(fn ($value, $row) => "<span class='{$row->status->class()}'> {$row->expected_at?->diffForHumans()}</span>")
                ->html()
                ->sortable(),
            Column::make('Assigned To', 'agent.name')
                ->format(fn ($value, $row) => $row->agent?->name)
                ->sortable()
                ->searchable(),
            Column::make('Actions', 'id')
                ->view('evaluate::tables.actions'),
        ];
    }

    public function filters(): array
    {
        $users = UserDepartmentRoleService::list($this->department);
        $super_users = User::query()
            ->whereHas('evaluateSuperAdmin')
            ->pluck('name', 'id');

        return [
            SelectFilter::make('Department')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    DepartmentService::list()->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->whereHas('department', function ($query) use ($value) {
                        $query->where('id', $value);
                    });
                }),
            SelectFilter::make('Subject')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    SubjectService::list()->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('subject_id', $value);
                }),
            SelectFilter::make('Assigned To')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    \Illuminate\Support\Arr::sort($super_users->toArray()
                    +
                    $users->toArray())
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('assigned_to', $value);
                }),
            SelectFilter::make('Priority')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    TicketPrioritiesEnum::asArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('priority', $value);
                }),
            SelectFilter::make('Completion Status')
                ->options(
                    [
                        '' => 'All',
                        'completed' => 'Completed',
                        'incompleted' => 'No Completed',

                    ]
                )->filter(function (Builder $builder, string $value) {
                    $builder->$value();
                }),
        ];
    }

    protected function withDefaultSorting()
    {
        $this->setDefaultSort('expected_at', 'asc');
    }

    protected function tableTitle(): string
    {
        return str(join(' ', [
            __('evaluate::messages.tickets'),
            __('evaluate::messages.for'),
            $this->department->name ?? 'All Departments'
        ]))->headline();
    }

    public function configure(): void
    {
        parent::configure();

        $this->setConfigurableAreas([
            'toolbar-right-start' => [
                'evaluate::livewire.dashboard._assigned_to_me', [
                    'user' => auth()->user(),
                ],
            ],
        ]);
    }

    public function filterAgent($id = null)
    {
        // dump($id);
        $this->agent_id = $id;
        $this->table['filters']['assigned_to'] = $id;
    }
}
