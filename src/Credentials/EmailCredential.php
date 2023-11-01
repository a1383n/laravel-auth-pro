<?php

namespace LaravelAuthPro\Credentials;

use LaravelAuthPro\Concerns\Credentials\OneTimePasswordConcerns;
use LaravelAuthPro\Concerns\Credentials\PasswordConcerns;
use LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

class EmailCredential extends AuthCredential implements EmailCredentialInterface
{
    use OneTimePasswordConcerns;
    use PasswordConcerns;

    protected ?string $password;
    protected ?string $token;
    protected ?string $code;

    public function getEmail(): string
    {
        return $this->identifier->getIdentifierValue();
    }

    public function getPassword(): ?string
    {
        return $this->password ?? null;
    }

    public function getSupportedIdentifiersTypes(): array
    {
        return [
            AuthIdentifierType::EMAIL,
        ];
    }

    public function getOneTimePassword(): ?string
    {
        return $this->code ?? null;
    }

    public function getOneTimePasswordToken(): ?string
    {
        return $this->token ?? null;
    }
}
