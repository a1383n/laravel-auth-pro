<?php

namespace LaravelAuthPro\Credentials;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;
use LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use Illuminate\Support\Arr;
use LaravelAuthPro\Traits\HasPayload;

/**
 * @implements HasBuilderInterface<AuthCredentialInterface>
 */
abstract class AuthCredential implements AuthCredentialInterface, HasBuilderInterface
{
    use HasPayload;

    /**
     * @inheritDoc
     */
    public function __construct(protected readonly string $providerId, protected readonly AuthIdentifierInterface $identifier, protected readonly AuthProviderSignInMethod $signInMethod, array $payload)
    {
        $this->throwIfIdentifierTypeNotSupported();
        $this->fillAttributes($payload);
    }

    public static function getBuilder(): AuthCredentialBuilder
    {
        return new AuthCredentialBuilder;
    }

    /**
     * @return void
     */
    public function throwIfIdentifierTypeNotSupported(): void
    {
        if (!in_array($this->identifier->getIdentifierType(), $this->getSupportedIdentifiersTypes()))
            throw new \InvalidArgumentException(sprintf('Invalid identifier type [%s] in %s', $this->identifier->getIdentifierType()->name, class_basename(static::class)));
    }

    public static function getPayloadRules(): array
    {
        $rules = [];

        if (method_exists(static::class, 'getPasswordRule')) {
            $rules = $rules + Arr::wrap(static::getPasswordRule());
        }

        if (method_exists(static::class, 'getOneTimePasswordRule')) {
            $rules = $rules + Arr::wrap(static::getOneTimePasswordRule());
        }

        return $rules;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getIdentifier(): AuthIdentifierInterface
    {
        return $this->identifier;
    }

    public function getSignInMethod(): AuthProviderSignInMethod
    {
        return $this->signInMethod;
    }
}
