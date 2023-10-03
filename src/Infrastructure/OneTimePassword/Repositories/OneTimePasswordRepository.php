<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Repositories;

use LaravelAuthPro\Base\BaseRepository;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource\OneTimePasswordDataSourceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\Repositories\OneTimePasswordRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

class OneTimePasswordRepository extends BaseRepository implements OneTimePasswordRepositoryInterface
{
    public function __construct(private readonly OneTimePasswordDataSourceInterface $dataSource)
    {
        //
    }

    public function createOneTimePasswordWithIdentifier(OneTimePasswordEntityInterface $entity): void
    {
        $this->dataSource->create($entity);
    }

    public function getOneTimePasswordWithIdentifierAndToken(AuthIdentifierInterface $identifier, string $token): ?OneTimePasswordEntityInterface
    {
        return $this->dataSource->get($identifier, $token);
    }

    public function isOneTimePasswordExists(AuthIdentifierInterface $identifier, string $token): bool
    {
        return $this->getOneTimePasswordWithIdentifierAndToken(...func_get_args()) !== null;
    }

    public function removeOneTimePassword(OneTimePasswordEntityInterface $entity): bool
    {
        return $this->dataSource->delete($entity);
    }
}
