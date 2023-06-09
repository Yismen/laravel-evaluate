<?php

namespace Dainsys\Evaluate\Models\Traits;

use Dainsys\Evaluate\Models\Reply;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyReplies
{
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
}
