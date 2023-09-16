<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Support;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
use LaravelAuthPro\Contracts\Base\GeneratorInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordCodeType;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordTokenType;

class CodeGenerator implements GeneratorInterface
{
    protected readonly int $length;
    protected readonly OneTimePasswordCodeType $type;

    public function __construct(Repository $configRepository)
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->length = $configRepository->get('auth_pro.one_time_password.code.length', 6);

        /**
         * @phpstan-ignore-next-line
         */
        $this->type = OneTimePasswordCodeType::from($configRepository->get('auth_pro.one_time_password.code.type', 'digit'));
    }

    public function generate(int $length = null): string
    {
        $length = $length ?? $this->length;

        return match ($this->type) {
            OneTimePasswordCodeType::DIGIT => $this->generateRandomDigit($length),
            OneTimePasswordCodeType::ALPHA => $this->generateRandomAlphabet($length),
        };
    }

    private function generateRandomDigit(int $length): string
    {
        return Str::password($length, false, true, false);
    }

    private function generateRandomAlphabet(int $length): string
    {
        return Str::lower(Str::password($length, true, false, false,));
    }
}
