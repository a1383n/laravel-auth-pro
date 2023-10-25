<?php

namespace LaravelAuthPro\Contracts\Credentials\Base;

use LaravelAuthPro\Contracts\AuthSignatureInterface;

interface HasSignatureInterface
{
    public function getSignature(): AuthSignatureInterface;
}
