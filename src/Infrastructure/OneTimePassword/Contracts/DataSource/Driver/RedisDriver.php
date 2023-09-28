<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource\Driver;

use Illuminate\Cache\RedisStore;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource\DriverInterface;

class RedisDriver extends RedisStore implements DriverInterface
{
    public function wrapKey(string $key): string
    {
        return $this->getPrefix() . ':' . $key;
    }

    public function delete(...$arguments): int
    {
        return $this->connection()->unlink(...$arguments);
    }
}
