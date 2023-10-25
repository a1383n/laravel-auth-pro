<?php

namespace LaravelAuthPro\Contracts;

interface AuthExceptionInterface extends \Throwable
{
    public function getErrorMessage(): string;
}
