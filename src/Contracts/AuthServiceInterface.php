<?php

namespace LaravelAuthPro\Contracts;

interface AuthServiceInterface
{
    public function loginWithCredential(AuthCredentialInterface $credential): AuthResultInterface;

    public function sendOneTimePassword(AuthIdentifierInterface $identifier): AuthResultInterface;
}
