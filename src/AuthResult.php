<?php

namespace LaravelAuthPro;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthExceptionInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthResultInterface;
use LaravelAuthPro\Traits\HasBuilder;

class AuthResult implements AuthResultInterface
{
    use HasBuilder;

    /**
     * @param AuthIdentifierInterface|null $identifier
     * @param AuthenticatableInterface|null $user
     * @param AuthExceptionInterface|null $exception
     * @param array<string,string|mixed>|null $payload
     */
    public function __construct(protected ?AuthIdentifierInterface $identifier = null, protected ?AuthenticatableInterface $user = null, protected ?AuthExceptionInterface $exception = null, protected ?array $payload = null)
    {
        //
    }

    protected static function getBuilderClass(): string
    {
        return AuthResultBuilder::class;
    }

    public function getIdentifier(): ?AuthIdentifierInterface
    {
        return $this->identifier;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function isSuccessful(): bool
    {
        return $this->getException() === null;
    }

    public function getException(): ?AuthExceptionInterface
    {
        return $this->exception;
    }

    public function getUser(): ?AuthenticatableInterface
    {
        return $this->user;
    }

    public function throwIfError(): self
    {
        return ! $this->isSuccessful() ? throw $this->getException() : $this;
    }
}
