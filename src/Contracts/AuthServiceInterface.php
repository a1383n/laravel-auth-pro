<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface;

interface AuthServiceInterface
{
    public function loginWithCredential(AuthCredentialInterface $credential): AuthResultInterface;

    public function getOneTimePasswordSignature(PhoneCredentialInterface $phoneCredential, string $ip): AuthResultInterface;

    public function verifyOneTimePasswordSignature(AuthSignatureInterface $signature): AuthResultInterface;

    public function sendOneTimePassword(AuthIdentifierInterface $identifier): AuthResultInterface;
}
