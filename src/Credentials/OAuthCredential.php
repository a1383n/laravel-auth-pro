<?php

namespace LaravelAuthPro\Credentials;

use LaravelAuthPro\Contracts\Credentials\OAuthCredentialInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

class OAuthCredential extends AuthCredential implements OAuthCredentialInterface
{
    protected ?string $driver;
    protected ?string $email;
    protected ?string $idToken;

    public function getSupportedIdentifiersTypes(): array
    {
        return [AuthIdentifierType::EMAIL];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getIdToken(): string
    {
        return $this->idToken;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }
}
