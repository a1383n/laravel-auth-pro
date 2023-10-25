<?php

namespace LaravelAuthPro\Contracts\Credentials\Base;

interface HasOneTimePasswordInterface
{
    public function getOneTimePassword(): ?string;

    public function getOneTimePasswordToken(): ?string;

    /**
     * @return array<string, string|string[]>
     */
    public static function getOneTimePasswordRule(): array;
}
