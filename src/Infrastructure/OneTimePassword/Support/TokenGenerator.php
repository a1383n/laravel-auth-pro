<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Support;

use Illuminate\Support\Str;
use LaravelAuthPro\Contracts\Base\GeneratorInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordTokenType;

class TokenGenerator implements GeneratorInterface
{
    public function __construct(protected readonly OneTimePasswordTokenType $type = OneTimePasswordTokenType::RANDOM_STRING, protected readonly int $length = 8)
    {
        //
    }

    /**
     * @param int|null $length
     * @return string
     */
    public function generate(int $length = null): string
    {
        $length ??= $this->length;

        return match ($this->type) {
            OneTimePasswordTokenType::RANDOM_STRING => Str::random($length),
            OneTimePasswordTokenType::RANDOM_INT => (string)$this->generateRandomInt($length),
            OneTimePasswordTokenType::ULID, OneTimePasswordTokenType::UUID => (string)Str::{$this->type->value}(),
        };
    }

    private function generateRandomInt(int $length): int
    {
        if ($length > 18) {
            // It's reached PHP_MAX_INT value and will convert to float, but random_int method accept int as min and max.
            throw new \RuntimeException('$length is too large. Length should not above 18');
        }

        return random_int(10 ** ($length - 1), (10 ** $length) - 1);
    }
}
