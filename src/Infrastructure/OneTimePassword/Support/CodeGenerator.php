<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Support;

use Illuminate\Support\Str;
use LaravelAuthPro\Contracts\Base\GeneratorInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordCodeType;

class CodeGenerator implements GeneratorInterface
{
    public function __construct(protected readonly OneTimePasswordCodeType $type = OneTimePasswordCodeType::DIGIT, protected readonly int $length = 6)
    {
        //
    }

    public function generate(int $length = null): string
    {
        $length ??= $this->length;

        return match ($this->type) {
            OneTimePasswordCodeType::DIGIT => $this->generateRandomDigit($length),
            OneTimePasswordCodeType::ALPHA => $this->generateRandomAlphabet($length),
        };
    }

    private function generateRandomDigit(int $length): string
    {
        if (method_exists(Str::class, 'password')) {
            return Str::password($length, false, true, false);
        } else {
            $s = '';
            $digits = '0123456789';

            for ($i = 0; $i < $length; $i++) {
                $s .= $digits[random_int(0, strlen($digits) - 1)];
            }

            return $s;
        }
    }

    private function generateRandomAlphabet(int $length): string
    {
        if (method_exists(Str::class, 'password')) {
            return Str::lower(Str::password($length, true, false, false));
        } else {
            $s = '';
            $characters = 'abcdefghijklmnopqrstuvwxyz';

            for ($i = 0; $i < $length; $i++) {
                $s .= $characters[random_int(0, strlen($characters) - 1)];
            }

            return $s;
        }
    }
}
