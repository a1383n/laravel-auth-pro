<?php

namespace LaravelAuthPro\Contracts;

use Throwable;

interface AuthExceptionInterface extends Throwable
{
    public function getErrorMessage(): string;

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array;
}
