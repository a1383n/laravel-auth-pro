<?php

namespace LaravelAuthPro\Base;


use LaravelAuthPro\Contracts\Base\BaseRepositoryInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;

/**
 * @template TRepo
 *
 * @property-read TRepo $repository
 */
abstract class BaseService implements BaseServiceInterface
{
    public function __construct(protected readonly ?BaseRepositoryInterface $repository = null)
    {
        //
    }

    public function hasRepository(): bool
    {
        return $this->repository !== null;
    }

    public function throwIfRepositoryNotProvided(): void
    {
        if (!$this->hasRepository())
            throw new \InvalidArgumentException('$repository not provided');
    }
}
