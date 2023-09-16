<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordServiceInterface extends BaseServiceInterface
{
    public function createOneTimePasswordWithIdentifier(AuthIdentifierInterface $identifier): OneTimePasswordEntityInterface;

    public function verifyOneTimePassword(AuthIdentifierInterface $identifier, string $token, string $code): OneTimePasswordResultInterface;
}
