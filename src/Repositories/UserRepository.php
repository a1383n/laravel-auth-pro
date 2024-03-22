<?php

namespace LaravelAuthPro\Repositories;

use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use LaravelAuthPro\Contracts\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected ?string $userModel = null)
    {
        if ($this->userModel === null) {
            $this->setUserModelClass();
        }
    }

    public function setUserModelClass(?string $model = null): UserRepositoryInterface
    {
        $this->userModel ??= $model ?? AuthPro::getDefaultAuthenticatableModel();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUserByIdentifier(AuthIdentifierInterface $identifier, array $columns = ['*']): ?AuthenticatableInterface
    {
        /**
         * @var AuthenticatableInterface|null $user
         */
        $user = $this->userModel::query()
            ->whereIdentifier($identifier)
            ->first($columns);

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function isUserExist(AuthIdentifierInterface $identifier): bool
    {
        return $this->userModel::query()
            ->whereIdentifier($identifier)
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function getUserById(string $id, array $columns = ['*']): ?AuthenticatableInterface
    {
        /**
         * @var AuthenticatableInterface|null $user
         */
        $user = $this->userModel::query()
            ->find($id, $columns);

        return $user;
    }

    public function createByAuthenticatable(AuthIdentifierInterface $identifier, AuthenticatableInterface $authenticatable): bool
    {
        if ($this->isUserExist($identifier)) {
            throw new AuthException('user_already_exits');
        }

        return $authenticatable->save();
    }
}
