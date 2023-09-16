<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;

interface OneTimePasswordVerifierServiceInterface extends BaseServiceInterface
{
    public function verify(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): OneTimePasswordVerifyResultInterface;

    public function check(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): bool;
}
