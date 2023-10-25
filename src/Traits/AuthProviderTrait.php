<?php

namespace LaravelAuthPro\Traits;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait AuthProviderTrait
{
    public function getProviderId(): string
    {
        return $this->provider_id;
    }

    /**
     * @inheritDoc
     */
    public function getProviderPayload(): array
    {
        return $this->payload;
    }

    /**
     * @inheritDoc
     */
    public function setProviderPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function getVerifiedAt(): ?CarbonImmutable
    {
        return $this->verified_at;
    }

    public function setVerifiedAt(CarbonInterface $datetime): void
    {
        $this->verified_at = $datetime->toImmutable();
    }
}
