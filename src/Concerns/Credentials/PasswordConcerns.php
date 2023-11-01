<?php

namespace LaravelAuthPro\Concerns\Credentials;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use LaravelAuthPro\Contracts\Credentials\Base\HasPasswordInterface;

/**
 * @mixin HasPasswordInterface
 */
trait PasswordConcerns
{
    public static function getPasswordRule(): array
    {
        return [
            'password' => self::getPasswordPropertyRule(),
        ];
    }

    protected static function getPasswordPropertyRule(callable|bool $isRequired = true, bool $validateAsString = true): array
    {
        return [
            Rule::requiredIf($isRequired),
            $validateAsString ? 'string' : Password::default(),
        ];
    }
}
