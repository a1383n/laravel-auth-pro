<?php

namespace LaravelAuthPro\Credentials;

use LaravelAuthPro\AuthSignature;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

class PhoneCredential extends AuthCredential implements PhoneCredentialInterface
{
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

    public function getSignature(): AuthSignatureInterface
    {
        return AuthSignature::getBuilder()
            ->fromEncryptedPlainSignature($this->signature ?? throw new \Exception('signature not provided'));
    }
}
