<?php

namespace Dainsys\Evaluate\Models\Traits;

use Dainsys\Evaluate\Models\Department;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToDepartment
{
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
