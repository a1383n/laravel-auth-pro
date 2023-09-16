<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Contracts\Base\BaseServiceInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordVerifierServiceInterface extends BaseServiceInterface
{
    public function verify(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): OneTimePasswordVerifyResultInterface;

    public function check(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): bool;
}
