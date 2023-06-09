<?php

namespace Dainsys\Evaluate\Http\Livewire\Dashboard;

use Livewire\Component;
use Dainsys\Evaluate\Models\Department;
use Dainsys\Evaluate\Services\TicketService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dainsys\Evaluate\Services\Department\DepartmentListService;

class Index extends Component
{
    use AuthorizesRequests;

    public $department;
    public $selected;
    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'grabTicket'
    ];

    public function mount()
    {
        if (auth()->user()->isEvaluateSuperAdmin()) {
            $this->selected = null;
            $this->department = is_null($this->selected)
                ? new Department()
                : Department::find($this->selected);

            return;
        }

        if (!auth()->user()->hasAnyDepartmentRole()) {
            return redirect()->route('evaluate.my_tickets');
        }

        $this->department = auth()->user()->department;
        $this->selected = $this->department->id;
    }

    public function render()
    {
        $this->authorize('view-dashboards');

        return view('evaluate::livewire.dashboard.index', [
            'department' => $this->department,
            'departments' => DepartmentListService::withTicketsOnly()->pluck('name', 'id')->toArray(),
            'total_tickets' => TicketService::byDepartment($this->selected)->count(),
            'tickets_open' => TicketService::byDepartment($this->selected)->incompleted()->count(),
            'completion_rate' => TicketService::completionRate($this->selected),
            'compliance_rate' => TicketService::complianceRate($this->selected),
            'satisfaction_rate' => TicketService::satisfactionRate($this->selected),
        ])
        ->layout('evaluate::layouts.app');
    }

    public function updatedSelected($value)
    {
        if (empty($value)) {
            $this->selected = null;
        }

        $this->department = is_null($this->selected)
        ? new Department()
        : Department::find($this->selected);

        $this->emit('dashboardUpdated');
    }
}
