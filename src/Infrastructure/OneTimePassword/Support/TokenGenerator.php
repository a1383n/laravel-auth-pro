<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Support;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
use LaravelAuthPro\Contracts\Base\GeneratorInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordTokenType;

class TokenGenerator implements GeneratorInterface
{
    protected readonly int $length;
    protected readonly OneTimePasswordTokenType $type;

    public function __construct(Repository $configRepository)
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->length = $configRepository->get('one_time_password.token.length', 8);

        /**
         * @phpstan-ignore-next-line
         */
        $this->type = OneTimePasswordTokenType::from($configRepository->get('one_time_password.token.type', 'random_string'));
    }

    /**
     * @param int|null $length
     * @return string
     */
    public function generate(int $length = null): string
    {
        $length = $length ?? $this->length;

        return match ($this->type) {
            OneTimePasswordTokenType::RANDOM_STRING => Str::random($length),
            OneTimePasswordTokenType::RANDOM_INT => (string)$this->generateRandomInt($length),
            OneTimePasswordTokenType::ULID => (string)Str::ulid(),
            OneTimePasswordTokenType::UUID => (string)Str::uuid(),
        };
    }

    private function generateRandomInt(int $length): int
    {
        return random_int(10 ** ($length - 1), (10 ** $length) - 1);
    }
}
