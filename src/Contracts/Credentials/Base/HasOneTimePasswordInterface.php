<?php

namespace LaravelAuthPro\Contracts\Credentials\Base;

use Illuminate\Validation\Rule;

interface HasOneTimePasswordInterface
{
    public function getOneTimePassword(): ?string;

    public function getOneTimePasswordToken(): ?string;

    /**
     * @return array<string, array<int, string|Rule>>
     */
    public static function getOneTimePasswordRule(): array;
}
