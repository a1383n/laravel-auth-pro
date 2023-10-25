<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Repositories;

use Illuminate\Redis\Connections\Connection;
use LaravelAuthPro\Base\BaseRepository;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordVerifierRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Traits\HasKeyPrefix;

class OneTimePasswordVerifierRepository extends BaseRepository implements OneTimePasswordVerifierRepositoryInterface
{
    use HasKeyPrefix;

    public function __construct(private readonly Connection $connection)
    {
        self::$prefix = 'otp';
    }

    public function getFailedAttemptsCount(OneTimePasswordEntityInterface $entity): int
    {
        /**
         * @var null|int|false $result
         */
        $result = $this->connection->get(self::getKey($entity->getKey()));

        return $result === false || $result === null ? 0 : $result;
    }

    public function incrementFailAttemptsCount(OneTimePasswordEntityInterface $entity, int $value = 1): int
    {
        /**
         * @phpstan-ignore-next-line
         */
        $value = $this->connection->incr($key = self::getKey($entity->getKey()),$value);

        $this->connection->expire($key, (int)$entity->getValidInterval()->addDays(1)->totalSeconds);

        return $value;
    }
}
