<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthResultInterface;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;
use LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordServiceInterface extends BaseServiceInterface
{
    public function createOneTimePasswordWithIdentifier(AuthIdentifierInterface $identifier): OneTimePasswordEntityInterface;

    public function verifyOneTimePassword(AuthIdentifierInterface $identifier, PhoneCredentialInterface $credential, bool $dry = false): OneTimePasswordResultInterface;

    public function verifyOneTimePasswordSignature(AuthSignatureInterface $signature): AuthResultInterface;
}
