<?php

namespace Dainsys\Evaluate\Models\Traits;

use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTickets
{
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
