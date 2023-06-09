<?php

namespace Dainsys\Evaluate\Enums;

use Dainsys\Evaluate\Enums\Traits\AsArray;

enum DepartmentRolesEnum: string implements EnumContract
{
    use AsArray;
    case Admin = 'admin';
    case Agent = 'agent';
}
