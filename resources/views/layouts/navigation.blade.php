<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @can('view-dashboards')
            <li class="nav-item">
                <a href="{{ route('evaluate.dashboard') }}"
                    class="nav-link {{ (request()->is('evaluate/dashboard*') || request()->is('evaluate/admin') || request()->is('evaluate')) ? 'active' : '' }}">
                    <i class="nav-icon fa fa-dashboard"></i>
                    <p>
                        {{ str(__('evaluate::messages.dashboard'))->headline() }}
                    </p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Evaluate\Models\Department::class)
            <li class="nav-item">
                <a href="{{ route('evaluate.admin.departments.index') }}" target="_top"
                    class="nav-link {{ (request()->is('evaluate/departments*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('evaluate::messages.departments'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Evaluate\Models\Subject::class)
            <li class="nav-item">
                <a href="{{ route('evaluate.admin.subjects.index') }}" target="_top"
                    class="nav-link {{ (request()->is('evaluate/subjects*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('evaluate::messages.subjects'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Evaluate\Models\DepartmentRole::class)
            <li class="nav-item">
                <a href="{{ route('evaluate.admin.department_roles.index') }}" target="_top"
                    class="nav-link {{ (request()->is('evaluate/department_roles*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('evaluate::messages.department_roles'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Evaluate\Models\EvaluateSuperAdmin::class)
            <li class="nav-item">
                <a href="{{ route('evaluate.admin.evaluate_super_admins.index') }}" target="_top"
                    class="nav-link {{ (request()->is('evaluate/evaluate_super_admins*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('evaluate::messages.evaluate_super_admins'))->headline() }}</p>
                </a>
            </li>
            @endcan

            <li class="nav-item">
                <a href="{{ route('evaluate.my_tickets') }}"
                    class="nav-link {{ (request()->is('evaluate/my_tickets*')) ? 'active' : '' }}">
                    <i class="nav-icon fa fa-ticket"></i>
                    <p>
                        {{ str(__('evaluate::messages.my_tickets'))->headline() }}
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->