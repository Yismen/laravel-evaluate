<?php

namespace Dainsys\Evaluate\Models\Traits;

use Dainsys\Evaluate\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTicket
{
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
