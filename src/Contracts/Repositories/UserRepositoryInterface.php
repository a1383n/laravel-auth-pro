<?php

namespace LaravelAuthPro\Contracts\Repositories;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param AuthIdentifierInterface $identifier
     * @return bool
     */
    public function isUserExist(AuthIdentifierInterface $identifier): bool;

    /**
     * @param AuthIdentifierInterface $identifier
     * @param string[] $columns
     * @return AuthenticatableInterface|null
     */
    public function getUserByIdentifier(AuthIdentifierInterface $identifier, array $columns = ['*']): ?AuthenticatableInterface;
}
