<?php

namespace Dainsys\Evaluate\Http\Livewire\Department;

use Livewire\Component;
use Dainsys\Evaluate\Models\Department;
use Dainsys\Evaluate\Services\DepartmentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'departmentUpdated' => '$refresh',
    ];

    public function render()
    {
        $this->authorize('viewAny', new Department());

        return view('evaluate::livewire.department.index', [
            'departments' => DepartmentService::list()
        ])
        ->layout('evaluate::layouts.app');
    }
}
