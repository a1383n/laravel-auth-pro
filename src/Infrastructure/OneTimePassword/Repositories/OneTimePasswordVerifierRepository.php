<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Repositories;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\RateLimiter;
use LaravelAuthPro\Base\BaseRepository;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource\DataSourceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\Repositories\OneTimePasswordVerifierRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Traits\HasKeyPrefix;

class OneTimePasswordVerifierRepository extends BaseRepository implements OneTimePasswordVerifierRepositoryInterface
{
    private const FAILED_ATTEMPTS_KEY = 'failed_attempts';

    public function __construct(private readonly DataSourceInterface $dataSource)
    {
        //
    }

    public function getFailedAttemptsCount(OneTimePasswordEntityInterface $entity): int
    {
        /**
         * @var int|false $result
         */
        $result = $this->connection->hGet(self::FAILED_ATTEMPTS_KEY, self::getKey($entity->getKey()));

        return $result === false ? 0 : $result;
    }

    public function incrementFailAttemptsCount(OneTimePasswordEntityInterface $entity, int $value = 1): int
    {
        return $this->connection->hIncrBy(self::FAILED_ATTEMPTS_KEY, self::getKey($entity->getKey()), $value);
    }
}
