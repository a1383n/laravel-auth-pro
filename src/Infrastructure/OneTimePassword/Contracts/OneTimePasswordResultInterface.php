<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

interface OneTimePasswordResultInterface
{
    public function isSuccessful(): bool;

    public function getError(): mixed;

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array;
}
