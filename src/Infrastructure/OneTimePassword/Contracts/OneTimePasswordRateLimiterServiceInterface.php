<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordRateLimiterServiceInterface extends BaseServiceInterface
{
    public function passesRequest(AuthIdentifierInterface $identifier): bool;

    public function passesVerify(AuthIdentifierInterface $identifier, OneTimePasswordEntityInterface $entity): bool;

    public function passesFailed(AuthIdentifierInterface $identifier, OneTimePasswordEntityInterface $entity): bool;
}
