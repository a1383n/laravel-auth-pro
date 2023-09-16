<?php

namespace LaravelAuthPro\Contracts\Credentials\Base;

use Illuminate\Contracts\Validation\ValidationRule;

interface HasPasswordInterface
{
    public function getPassword(): ?string;

    /**
     * @return array<string, string|string[]|ValidationRule>
     */
    public static function getPasswordRule(): array;
}
