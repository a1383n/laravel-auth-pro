<?php

namespace LaravelAuthPro\Traits;

trait HasKeyPrefix
{
    protected static string $prefix;
    protected static string $separator = ':';

    protected static function getKey(string $key): string
    {
        return self::$prefix . self::$separator . $key;
    }
}
