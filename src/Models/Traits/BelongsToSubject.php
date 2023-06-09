<?php

namespace Dainsys\Evaluate\Models\Traits;

use Dainsys\Evaluate\Models\Subject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSubject
{
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
