<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordDataSourceInterface extends DataSourceInterface
{
    public function create(OneTimePasswordEntityInterface $entity): void;

    public function get(AuthIdentifierInterface $identifier, string $token): ?OneTimePasswordEntityInterface;

    public function delete(OneTimePasswordEntityInterface $entity): bool;

    public function incrementKey(string $key, $value = 1);
}
