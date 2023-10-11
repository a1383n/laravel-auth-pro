<?php

namespace LaravelAuthPro;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthExceptionConverterInterface;
use LaravelAuthPro\Contracts\AuthExceptionInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthResultInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;

/**
 * @implements EntityBuilderInterface<AuthResultInterface>
 */
class AuthResultBuilder implements EntityBuilderInterface
{
    private AuthIdentifierInterface $identifier;

    /**
     * @var array<string, string>|null
     */
    private ?array $payload = null;

    private ?AuthExceptionInterface $exception = null;

    private ?AuthenticatableInterface $user = null;

    public function __construct(private readonly AuthExceptionConverterInterface $exceptionConverter)
    {
        //
    }

    public function as(AuthIdentifierInterface $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function successful(?AuthenticatableInterface $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    public function failed(AuthExceptionInterface $exception): self
    {
        $this->exception = $this->exceptionConverter->convert($exception);

        return $this;
    }

    /**
     * @param array<string, string> $payload
     * @return $this
     */
    public function with(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function build(): AuthResultInterface
    {
        return new AuthResult($this->identifier, $this->user, $this->exception, $this->payload);
    }
}
