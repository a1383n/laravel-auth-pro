<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Contracts\Base\HasBuilderInterface;

interface AuthResultInterface extends HasBuilderInterface
{
    public function isSuccessful(): bool;

    public function throwIfError(): self;

    public function getIdentifier(): ?AuthIdentifierInterface;

    public function getException(): ?AuthExceptionInterface;

    public function getUser(): ?AuthenticatableInterface;

    /**
     * @return array<string, string|mixed>|null
     */
    public function getPayload(): ?array;
}
