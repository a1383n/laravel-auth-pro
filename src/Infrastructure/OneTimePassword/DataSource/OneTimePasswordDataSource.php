<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\DataSource;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource\Driver\RedisDriver;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource\DriverInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource\OneTimePasswordDataSourceInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Model\OneTimePasswordEntity;

class OneTimePasswordDataSource implements OneTimePasswordDataSourceInterface
{
    public function __construct(protected readonly DriverInterface $driver)
    {
        //
    }

    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }

    public function create(OneTimePasswordEntityInterface $entity): void
    {
        if ($this->driver instanceof RedisDriver) {
            ($connection = $this->driver->connection())->hMSet($key = $this->driver->wrapKey($entity->getKey()), $entity->toArray());
            $connection->expire($key, intval($entity->getValidInterval()->totalSeconds * 2));
        } else {
            throw new \RuntimeException('Not implemented');
        }
    }

    public function get(AuthIdentifierInterface $identifier, string $token): ?OneTimePasswordEntityInterface
    {
        if ($this->driver instanceof RedisDriver) {
            $key = OneTimePasswordEntity::getKeyStatically($identifier, $token);

            /**
             * @var array<string, string>|false|null $result
             */
            $result = $this->driver->connection()->hGetAll($this->driver->wrapKey($key));

            if (! is_array($result) || empty($result)) {
                return null;
            }

            return OneTimePasswordEntity::getBuilder()::fromArray($identifier, $key, $result);
        } else {
            throw new \RuntimeException('Not implemented');
        }
    }

    public function delete(OneTimePasswordEntityInterface $entity): bool
    {
        if ($this->driver instanceof RedisDriver) {
            return $this->driver->delete($this->driver->wrapKey($entity->getKey())) === 1;
        } else {
            throw new \RuntimeException('Not implemented');
        }
    }
}
