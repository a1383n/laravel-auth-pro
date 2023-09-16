<?php

namespace LaravelAuthPro\Model\Contracts;

use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Support\Arrayable;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;

/**
 * @extends Arrayable<string, string>
 */
interface OneTimePasswordEntityInterface extends Arrayable
{
    public function getKey(): string;

    public static function getKeyStatically(AuthIdentifierInterface $identifier, string $token): string;

    public function getIdentifier(): AuthIdentifierInterface;

    public function getToken(): string;

    public function getCode(): string;

    public function isRecentlyCreated(): bool;

    public function getValidInterval(): CarbonInterval;

    public function getCreatedAt(): CarbonInterface;
}
