<?php

namespace LaravelAuthPro\Contracts\Credentials;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasEmailInterface;

interface GoogleCredentialInterface extends AuthCredentialInterface, HasEmailInterface
{
    public function getIdToken(): string;
}
