<?php

namespace LaravelAuthPro\Credentials;

use LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

class EmailCredential extends AuthCredential implements EmailCredentialInterface
{
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

    /**
     * @inheritDoc
     */
    public static function getOneTimePasswordRule(): array
    {
        return [
            'token' => ['required_if:credential.sign_in_method,otp', 'string', 'size:8'],
            'code' => ['required_if:credential.sign_in_method,otp', 'digits:6'],
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getPasswordRule(): array
    {
        return [
            'password' => ['required_if:credential.sign_in_method,password', 'string', 'min:8', 'max:32'],
        ];
    }
}
