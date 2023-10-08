<?php

namespace LaravelAuthPro\Model;

use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Hash;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Model\Builder\OneTimePasswordEntityBuilder;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

class OneTimePasswordEntity implements OneTimePasswordEntityInterface
{
    public function __construct(protected AuthIdentifierInterface $identifier, protected string $token, protected string $code, protected CarbonInterval $interval, protected CarbonInterface $createdAt)
    {
        //
    }

    public static function getBuilder(): OneTimePasswordEntityBuilder
    {
        return Container::getInstance()
            ->make(OneTimePasswordEntityBuilder::class);
    }

    public function getKey(): string
    {
        return self::getKeyStatically($this->identifier, $this->token);
    }

    public static function getKeyStatically(AuthIdentifierInterface $identifier, string $token): string
    {
        return substr(hash('sha256', $identifier->getIdentifierValue()), 0, 16) . ':' . $token;
    }

    public function getIdentifier(): AuthIdentifierInterface
    {
        return $this->identifier;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValidInterval(): CarbonInterval
    {
        return $this->interval;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function isRecentlyCreated(): bool
    {
        return method_exists(Hash::class, 'isHashed') ? Hash::isHashed($this->code) : password_get_info($this->code)['algo'] !== null;
    }

    public function toArray(): array
    {
        return [
            'c' => ! $this->isRecentlyCreated() ? Hash::make($this->getCode()) : $this->getCode(),
            'i' => $this->interval->totalSeconds,
            't' => $this->getCreatedAt()->timestamp,
        ];
    }
}
