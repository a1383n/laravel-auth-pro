<?php

namespace LaravelAuthPro\Contracts\Credentials;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasEmailInterface;

interface OAuthCredentialInterface extends AuthCredentialInterface, HasEmailInterface
{
    public function getIdToken(): string;

    public function getDriver(): string;
}
