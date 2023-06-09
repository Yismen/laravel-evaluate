<?php

namespace Dainsys\Evaluate\Enums\Traits;

trait AsArray
{
    public static function asArray(): array
    {
        $return = [];

        foreach (self::cases() as $case) {
            $return[$case->value] = $case->name;
        }

        return $return;
    }
}
