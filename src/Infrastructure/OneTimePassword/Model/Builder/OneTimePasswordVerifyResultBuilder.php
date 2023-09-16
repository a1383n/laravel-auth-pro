<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Model\Builder;

use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifyResultInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Model\OneTimePasswordVerifyResult;

/**
 * @implements EntityBuilderInterface<OneTimePasswordVerifyResultInterface>
 */
class OneTimePasswordVerifyResultBuilder implements EntityBuilderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $payload;
    protected ?OneTimePasswordVerifyError $error = null;

    /**
     * @param array<string, mixed> $payload
     * @return $this
     */
    public function successful(array $payload = []): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @param OneTimePasswordVerifyError $error
     * @param array<string, mixed> $payload
     * @return $this
     */
    public function failed(OneTimePasswordVerifyError $error, array $payload = []): self
    {
        $this->payload = $payload;
        $this->error = $error;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): OneTimePasswordVerifyResultInterface
    {
        return new OneTimePasswordVerifyResult($this->error, $this->payload);
    }
}
