<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts;

use LaravelAuthPro\Contracts\Base\BaseRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordVerifierRepositoryInterface extends BaseRepositoryInterface
{
    public function getFailedAttemptsCount(OneTimePasswordEntityInterface $entity): int;

    public function incrementFailAttemptsCount(OneTimePasswordEntityInterface $entity, int $value = 1): int;
}
