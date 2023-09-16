<?php

namespace LaravelAuthPro;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthExceptionInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthResultInterface;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;
use Illuminate\Container\Container;

/**
 * @implements HasBuilderInterface<AuthResultInterface>
 */
class AuthResult implements AuthResultInterface, HasBuilderInterface
{
    /**
     * @param AuthIdentifierInterface $identifier
     * @param AuthenticatableInterface|null $user
     * @param AuthExceptionInterface|null $exception
     * @param array<string,string>|null $payload
     */
    public function __construct(protected AuthIdentifierInterface $identifier, protected ?AuthenticatableInterface $user = null, protected ?AuthExceptionInterface $exception = null, protected ?array $payload = null)
    {
        //
    }

    public static function getBuilder(): AuthResultBuilder
    {
        return Container::getInstance()
            ->make(AuthResultBuilder::class);
    }

    public function getIdentifier(): AuthIdentifierInterface
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
}