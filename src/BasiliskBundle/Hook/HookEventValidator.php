<?php

namespace BasiliskBundle\Hook;

class HookEventValidator
{
    public static function nameIs(array $event, $value): bool
    {
        return self::valueIs($event, 'name', $value);
    }

    public static function eventIs(array $event, $value): bool
    {
        return self::valueIs($event, 'event', $value);
    }

    public static function valueIs(array $event, string $key, $value): bool
    {
        return self::keyExists($event, $key) && $event[$key] === $value;
    }

    public static function keyExists(array $event, string $key): bool
    {
        return isset($event[$key]);
    }
}
