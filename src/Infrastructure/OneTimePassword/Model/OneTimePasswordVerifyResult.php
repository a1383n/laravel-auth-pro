<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Model;

use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifyResultInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Model\Builder\OneTimePasswordVerifyResultBuilder;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;

/**
 * @implements HasBuilderInterface<OneTimePasswordVerifyResultInterface>
 */
class OneTimePasswordVerifyResult extends OneTimePasswordResult implements OneTimePasswordVerifyResultInterface, HasBuilderInterface
{
    /**
     * @param OneTimePasswordVerifyError|null $verifyError
     * @param array<string, mixed> $payload
     */
    public function __construct(protected ?OneTimePasswordVerifyError $verifyError, array $payload = [])
    {
        parent::__construct($this->verifyError, $payload);
    }

    /**
     * @inheritDoc
     */
    public static function getBuilder(): OneTimePasswordVerifyResultBuilder
    {
        return new OneTimePasswordVerifyResultBuilder;
    }

    public function getVerifierError(): ?OneTimePasswordVerifyError
    {
        return $this->verifyError;
    }
}
