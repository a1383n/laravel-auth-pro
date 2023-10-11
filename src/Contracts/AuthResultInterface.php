<?php

namespace LaravelAuthPro\Contracts;

interface AuthResultInterface
{
    public function isSuccessful(): bool;

    public function throwIfError(): void;

    public function getIdentifier(): AuthIdentifierInterface;

    public function getException(): ?AuthExceptionInterface;

    public function getUser(): ?AuthenticatableInterface;

    /**
     * @return array<string, string>|null
     */
    public function getPayload(): ?array;
}
