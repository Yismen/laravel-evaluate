<?php

namespace Dainsys\Evaluate\Models\Traits;

use Dainsys\Evaluate\Models\Subject;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManySubjects
{
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
