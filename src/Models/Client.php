<?php

namespace Dainsys\Evaluate\Models;

use Dainsys\Evaluate\Models\Traits\HasManyTickets;
use Dainsys\Evaluate\Models\Traits\HasManySubjects;
use Dainsys\Evaluate\Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Dainsys\Evaluate\Models\Traits\HasShortDescription;

class Client extends AbstractModel
{
    use HasManySubjects;
    use HasManyTickets;
    use HasShortDescription;
    protected $fillable = ['name', 'description', 'status'];

    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }
}
