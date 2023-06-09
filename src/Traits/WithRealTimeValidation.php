<?php

namespace Dainsys\Evaluate\Traits;

trait WithRealTimeValidation
{
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
