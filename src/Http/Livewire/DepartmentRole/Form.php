<?php

namespace Dainsys\Evaluate\Http\Livewire\DepartmentRole;

use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Dainsys\Evaluate\Models\Department;
use Dainsys\Evaluate\Models\DepartmentRole;
use Dainsys\Evaluate\Enums\DepartmentRolesEnum;
use Dainsys\Evaluate\Services\DepartmentService;
use Dainsys\Evaluate\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dainsys\Evaluate\Http\Livewire\Traits\HasSweetAlertConfirmation;

class Form extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;
    use HasSweetAlertConfirmation;

    protected $listeners = [
        'updateDepartmentRole',
    ];

    public bool $editing = false;
    public string $modal_event_name_form = 'showDepartmentRoleFormModal';

    public $departments;
    public $roles;

    public $department;
    public $role;
    public $user;

    public function mount()
    {
        $this->departments = DepartmentService::list();
        $this->roles = DepartmentRolesEnum::asArray();
    }

    public function render()
    {
        return view('evaluate::livewire.department_role.form')
        ->layout('evaluate::layouts.app');
    }

    public function updateDepartmentRole(User $user)
    {
        $this->reset(['department', 'user', 'role']);
        $this->department = optional($user->departmentRole)->department_id;
        $this->role = optional($user->departmentRole)->role;

        $this->user = $user;
        $this->authorize('update', new DepartmentRole());
        $this->editing = true;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function update()
    {
        $this->authorize('update', new DepartmentRole());
        $this->validate();

        DepartmentRole::updateOrCreate(
            ['user_id' => $this->user->id],
            ['department_id' => $this->department, 'role' => $this->role]
        );

        $this->dispatchBrowserEvent('closeAllModals');

        $this->editing = false;

        $this->emit('departmentUpdated');

        evaluateFlash('Department Role updated!', 'success');
    }

    protected function getRules()
    {
        return [
            'department' => [
                'required',
                Rule::exists(Department::class, 'id')
            ],
            'role' => [
                'required',
                new Enum(DepartmentRolesEnum::class)
            ]
        ];
    }

    public function deleteRole()
    {
        $this->confirm('deleteRoleConfirmed', 'Are you sure you want to remove this role from this user');
    }

    public function deleteRoleConfirmed()
    {
        $department_role = $this->user->departmentRole;

        $department_role->delete();

        flasher("Role removed for user {$this->user->name}", 'error');

        $this->emit('departmentUpdated');
        $this->dispatchBrowserEvent('closeAllModals');
    }
}
