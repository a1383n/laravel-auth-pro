<?php

namespace LaravelAuthPro\Contracts\Base;

/**
 * @template TEntity
 */
interface HasBuilderInterface
{
    /**
     * @return EntityBuilderInterface<TEntity>
     */
    public static function getBuilder(): EntityBuilderInterface;
}
