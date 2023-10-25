<?php

namespace LaravelAuthPro\Contracts\Credentials;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasEmailInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasOneTimePasswordInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasPasswordInterface;

interface EmailCredentialInterface extends AuthCredentialInterface, HasEmailInterface, HasPasswordInterface, HasOneTimePasswordInterface
{
    //
}
