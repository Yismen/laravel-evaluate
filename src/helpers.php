<?php

use Flasher\Prime\Notification\Envelope;
use Flasher\Prime\Notification\NotificationInterface;

if (function_exists('evaluateTableName') === false) {
    function evaluateTableName(string $name)
    {
        return config('evaluate.db_prefix') . $name;
    }
}

if (function_exists('str') === false) {
    function str(string|null $string)
    {
        return \Illuminate\Support\Str::of($string);
    }
}

if (function_exists('evaluateFlash') === false) {
    function evaluateFlash(string|null $message = null, string $type = NotificationInterface::SUCCESS, array $options = []): Envelope
    {
        return flasher($message, $type, $options);
    }
}
