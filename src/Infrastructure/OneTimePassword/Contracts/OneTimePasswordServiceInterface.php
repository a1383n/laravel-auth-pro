<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;

interface OneTimePasswordServiceInterface extends BaseServiceInterface
{
    public function createOneTimePasswordWithIdentifier(AuthIdentifierInterface $identifier): OneTimePasswordEntityInterface;

    public function verifyOneTimePassword(AuthIdentifierInterface $identifier, string $token, string $code): OneTimePasswordResultInterface;
}
