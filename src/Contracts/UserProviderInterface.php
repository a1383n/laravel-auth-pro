<?php

namespace LaravelAuthPro\Contracts;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

interface UserProviderInterface
{
    public function getProviderId(): string;

    /**
     * @return array<string, mixed>
     */
    public function getProviderPayload(): array;

    /**
     * @param array<string, mixed> $payload
     * @return void
     */
    public function setProviderPayload(array $payload): void;

    public function getVerifiedAt(): ?CarbonImmutable;

    public function setVerifiedAt(CarbonInterface $datetime): void;
}
