<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;

interface OneTimePasswordVerifyResultInterface extends OneTimePasswordResultInterface
{
    public function getVerifierError(): ?OneTimePasswordVerifyError;
}
