<?php

namespace LaravelAuthPro\Traits;

use Illuminate\Container\Container;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;

/**
 * @mixin HasBuilderInterface
 */
trait HasBuilder
{
    public static function getBuilder(): EntityBuilderInterface
    {
        return Container::getInstance()
            ->make(static::getBuilderClass());
    }

    protected static abstract function getBuilderClass(): string;
}
