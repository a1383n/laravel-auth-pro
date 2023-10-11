<?php

namespace LaravelAuthPro\Repositories;

use Illuminate\Contracts\Config\Repository;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var class-string<AuthenticatableInterface>
     */
    protected readonly string $userModel;

    public function __construct(Repository $configRepository)
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->userModel = $configRepository->get('auth_pro.authenticatable_model', 'App\\Models\\User');
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
            ->selectRaw('1')
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
}
