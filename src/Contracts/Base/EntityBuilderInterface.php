<?php

namespace LaravelAuthPro\Contracts\Base;

/**
 * @template TEntity
 */
interface EntityBuilderInterface
{
    /**
     * @return TEntity
     */
    public function build(): mixed;
}
