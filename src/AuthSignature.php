<?php

namespace LaravelAuthPro;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Crypt;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Model\Builder\AuthSignatureBuilder;
use LaravelAuthPro\Traits\HasBuilder;

class AuthSignature implements AuthSignatureInterface
{
    use HasBuilder;

    public function __construct(
        protected readonly string $id,
        protected readonly string $ip,
        protected readonly string $userId,
        protected readonly CarbonInterface $createdAt
    ) {
        //
    }

    protected static function getBuilderClass(): string
    {
        return AuthSignatureBuilder::class;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'ip' => $this->ip,
            'sub' => $this->userId,
            'iat' => $this->createdAt->timestamp,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRequestedIp(): string
    {
        return $this->ip;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTimestamp(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function __toString(): string
    {
        return Crypt::encrypt($this->toArray());
    }
}
