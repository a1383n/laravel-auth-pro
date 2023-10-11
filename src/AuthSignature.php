<?php

namespace LaravelAuthPro;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Crypt;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Model\Builder\AuthSignatureBuilder;

class AuthSignature implements AuthSignatureInterface
{
    public function __construct(
        protected readonly string $id,
        protected readonly string $ip,
        protected readonly string $userId,
        protected readonly CarbonInterface $timestamp
    ) {
        //
    }

    /**
     * @param array<string, string> $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        return new self($array['id'], $array['ip'], $array['sub'], Carbon::createFromTimestamp($array['iat']));
    }

    public static function getBuilder(): AuthSignatureBuilder
    {
        return new AuthSignatureBuilder();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'ip' => $this->ip,
            'sub' => $this->userId,
            'iat' => $this->timestamp,
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
        return $this->timestamp;
    }

    public function __toString(): string
    {
        return Crypt::encrypt($this->toArray());
    }
}
