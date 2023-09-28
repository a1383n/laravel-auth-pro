<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\Repositories;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordRepositoryInterface extends BaseRepositoryInterface
{
    public function createOneTimePasswordWithIdentifier(OneTimePasswordEntityInterface $entity): void;

    public function getOneTimePasswordWithIdentifierAndToken(AuthIdentifierInterface $identifier, string $token): ?OneTimePasswordEntityInterface;

    public function isOneTimePasswordExists(AuthIdentifierInterface $identifier, string $token): bool;

    public function removeOneTimePassword(OneTimePasswordEntityInterface $entity): bool;
}
