<?php

namespace LaravelAuthPro\Credentials;

use LaravelAuthPro\AuthSignature;
use LaravelAuthPro\Concerns\Credentials\OneTimePasswordConcerns;
use LaravelAuthPro\Concerns\Credentials\PasswordConcerns;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

class PhoneCredential extends AuthCredential implements PhoneCredentialInterface
{
    use OneTimePasswordConcerns;
    use PasswordConcerns;

    protected ?string $password;
    protected ?string $token = null;
    protected ?string $code;
    protected ?string $signature;

    public function getSupportedIdentifiersTypes(): array
    {
        return [
            AuthIdentifierType::MOBILE,
        ];
    }

    public function getOneTimePassword(): ?string
    {
        return $this->code;
    }

    public function getOneTimePasswordToken(): ?string
    {
        return $this->token;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPhone(): string
    {
        return $this->getIdentifier()->getIdentifierValue();
    }

    public function getSignature(): AuthSignatureInterface
    {
        return AuthSignature::getBuilder()
            ->fromEncryptedPlainSignature($this->signature ?? throw new \Exception('signature not provided'));
    }
}
