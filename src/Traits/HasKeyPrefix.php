<?php

namespace LaravelAuthPro\Traits;

trait HasKeyPrefix
{
    protected static string $prefix;

    protected static function getKey(string $key): string
    {
        return self::$prefix . ':' . $key;
    }
}
