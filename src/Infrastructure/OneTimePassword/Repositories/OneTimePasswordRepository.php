<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Repositories;

use Illuminate\Redis\Connections\Connection;
use LaravelAuthPro\Base\BaseRepository;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Model\OneTimePasswordEntity;
use LaravelAuthPro\Traits\HasKeyPrefix;

class OneTimePasswordRepository extends BaseRepository implements OneTimePasswordRepositoryInterface
{
    use HasKeyPrefix;

    public function __construct(private readonly Connection $connection)
    {
        self::$prefix = 'otp';
    }

    public function createOneTimePasswordWithIdentifier(OneTimePasswordEntityInterface $entity): bool
    {
        $this->connection->hMSet($key = self::getKey($entity->getKey()), $entity->toArray());

        return $this->connection->expire($key, intval($entity->getValidInterval()->totalSeconds * 2));
    }

    public function getOneTimePasswordWithIdentifierAndToken(AuthIdentifierInterface $identifier, string $token): ?OneTimePasswordEntityInterface
    {
        $key = OneTimePasswordEntity::getKeyStatically($identifier, $token);

        /**
         * @var array<string, string>|false|null $result
         */
        $result = $this->connection->hGetAll(self::getKey($key));

        if (! is_array($result) || empty($result)) {
            return null;
        }

        return OneTimePasswordEntity::getBuilder()::fromArray($identifier, $key, $result);
    }

    public function isOneTimePasswordExists(AuthIdentifierInterface $identifier, string $token): bool
    {
        $key = OneTimePasswordEntity::getKeyStatically($identifier, $token);

        return $this->connection->exists(self::getKey($key)) === 1;
    }

    public function removeOneTimePassword(OneTimePasswordEntityInterface $entity): bool
    {
        return $this->connection->unlink(self::getKey($entity->getKey())) === 1;
    }

    public function isSignatureUsed(string $signatureId): bool
    {
        return $this->connection->exists(self::getKey('signature:' . $signatureId)) === 1;
    }

    public function markSignatureAsUsed(string $signatureId, int $ttl): bool
    {
        return $this->connection->setex(self::getKey('signature:' . $signatureId), $ttl + 10, "1");
    }
}
