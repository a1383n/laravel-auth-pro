<?php

namespace LaravelAuthPro\Concerns\Credentials;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\RequiredIf;
use LaravelAuthPro\Contracts\Credentials\Base\HasPasswordInterface;

/**
 * @mixin HasPasswordInterface
 */
trait PasswordConcerns
{
    /**
     * @inheritDoc
     */
    public static function getPasswordRule(): array
    {
        return [
            'password' => self::getPasswordPropertyRule(),
        ];
    }

    /**
     * Get the rules for the password field.
     *
     * @param callable|bool $isRequired
     * @param bool $validateAsString
     * @return array<int,string|Rule|RequiredIf|Password>
     */
    public static function getPasswordPropertyRule(callable|bool $isRequired = true, bool $validateAsString = true): array
    {
        return [
            Rule::requiredIf($isRequired),
            $validateAsString ? 'string' : Password::default(),
        ];
    }
}
