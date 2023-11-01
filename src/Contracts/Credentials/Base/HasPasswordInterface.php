<?php

namespace LaravelAuthPro\Contracts\Credentials\Base;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\RequiredIf;

interface HasPasswordInterface
{
    public function getPassword(): ?string;

    /**
     * @return array<string, array<int,string|Rule|RequiredIf|Password>>
     */
    public static function getPasswordRule(): array;
}
