<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Repositories;

use LaravelAuthPro\Base\BaseRepository;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordVerifierRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Traits\HasKeyPrefix;
use Illuminate\Redis\Connections\Connection;

class OneTimePasswordVerifierRepository extends BaseRepository implements OneTimePasswordVerifierRepositoryInterface
{
    use HasKeyPrefix;

    private const FAILED_ATTEMPTS_KEY = 'failed_attempts';

    public function __construct(private readonly Connection $connection)
    {
        self::$prefix = 'otp';
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
