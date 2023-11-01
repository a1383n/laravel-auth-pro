<?php

namespace LaravelAuthPro\Concerns\Credentials;

use Illuminate\Validation\Rule;
use LaravelAuthPro\Contracts\Credentials\Base\HasOneTimePasswordInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordCodeType;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordTokenType;

/**
 * @mixin HasOneTimePasswordInterface
 */
trait OneTimePasswordConcerns
{
    /**
     * Get the rules for the one-time password fields.
     *
     * @return array
     */
    public static function getOneTimePasswordRule(): array
    {
        return [
            'token' => self::getOneTimePasswordTokenRule(),
            'code' => self::getOneTimePasswordCodeRule(),
        ];
    }

    /**
     * Get the rules for the one-time password token field.
     *
     * @return array
     */
    protected static function getOneTimePasswordTokenRule(): array
    {
        /**
         * @var OneTimePasswordTokenType $enumType
         */
        $enumType = config('auth_pro.one_time_password.token.type', OneTimePasswordTokenType::RANDOM_STRING);

        /**
         * @var int|null $length
         */
        $length = config('auth_pro.one_time_password.token.length', $enumType == OneTimePasswordTokenType::RANDOM_STRING || $enumType == OneTimePasswordTokenType::RANDOM_INT ? 6 : null);

        return [
            Rule::requiredIf(config('auth_pro.one_time_password.token.enabled', true)),
            ...self::mapTokenTypeToValidationRule($enumType, $length),
        ];
    }

    /**
     * Get the rules for the one-time password code field.
     *
     * @return array
     */
    protected static function getOneTimePasswordCodeRule(): array
    {
        /**
         * @var OneTimePasswordCodeType $enumType
         */
        $enumType = config('auth_pro.one_time_password.token.type', OneTimePasswordCodeType::DIGIT);

        /**
         * @var int $length
         */
        $length = config('auth_pro.one_time_password.code.length', 6);

        return [
            'required',
            ...self::mapCodeTypeToValidationRule($enumType, $length),
        ];
    }

    /**
     * Map the one-time password token type to a validation rule.
     *
     * @param OneTimePasswordTokenType $enumType
     * @param int|null $length
     * @return array
     */
    protected static function mapTokenTypeToValidationRule(OneTimePasswordTokenType $enumType, ?int $length): array
    {
        return match ($enumType) {
            OneTimePasswordTokenType::RANDOM_STRING => ['string', 'size:' . $length ?? throw new \InvalidArgumentException('$length cannot be null when type is' . $enumType->name)],
            OneTimePasswordTokenType::RANDOM_INT => ['int', 'digits:' . $length ?? throw new \InvalidArgumentException('$length cannot be null when type is' . $enumType->name)],
            OneTimePasswordTokenType::UUID, OneTimePasswordTokenType::ULID => [$enumType->value]
        };
    }

    /**
     * Map the one-time password code type to a validation rule.
     *
     * @param OneTimePasswordCodeType $enumType
     * @param int $length
     * @return array
     */
    protected static function mapCodeTypeToValidationRule(OneTimePasswordCodeType $enumType, int $length): array
    {
        return match ($enumType) {
            OneTimePasswordCodeType::DIGIT => ['digits:' . $length],
            OneTimePasswordCodeType::ALPHA => ['alpha:ascii', 'size:' . $length],
        };
    }
}
