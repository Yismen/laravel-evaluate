<?php

namespace Dainsys\Evaluate\Models\Traits;

use Dainsys\Evaluate\Models\Rating;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOneRating
{
    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }
}
