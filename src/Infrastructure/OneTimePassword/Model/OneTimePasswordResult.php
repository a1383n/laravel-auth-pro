<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Model;

use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordResultInterface;

class OneTimePasswordResult implements OneTimePasswordResultInterface
{
    /**
     * @param mixed $error
     * @param array<string, mixed> $payload
     */
    public function __construct(protected mixed $error, protected array $payload = [])
    {
        //
    }

    public function isSuccessful(): bool
    {
        return $this->error === null;
    }

    public function getError(): mixed
    {
        return $this->error;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
