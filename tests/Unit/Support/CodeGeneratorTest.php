<?php

use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordCodeType;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordTokenType;
use LaravelAuthPro\Infrastructure\OneTimePassword\Support\CodeGenerator;
use LaravelAuthPro\Infrastructure\OneTimePassword\Support\TokenGenerator;
use Symfony\Component\Uid\Ulid;

describe('test code generator', function () {
    it('generate random code with specified type', function (OneTimePasswordCodeType $type) {
        $generator = new CodeGenerator($type);

        $values = collect(range(1, 16))
            ->map(fn (int $i) => $i * 2)
            ->mapWithKeys(fn (int $length) => [$length => $generator->generate($length)]);

        expect($values)
            ->sequence(
                fn ($code, $length) => $code->toHaveLength((int)$length->value),
                fn ($code) => match ($type) {
                    OneTimePasswordCodeType::DIGIT => $code->toBeDigits(),
                    OneTimePasswordCodeType::ALPHA => $code->toBeAlpha(),
                }
            );
    })
        ->with([
            'digits' => [OneTimePasswordCodeType::DIGIT],
            'alpha' => [OneTimePasswordCodeType::ALPHA],
        ]);
});

describe('test token generator', function () {
    it('generate random token with specified type', function (OneTimePasswordTokenType $type, int $endRange = 16) {
        $generator = new TokenGenerator($type);

        $values = collect(range(1, $endRange))
            ->map(fn (int $i) => $i * 2)
            ->mapWithKeys(fn (int $length) => [$length => $generator->generate($length)]);

        expect($values)
            ->each(
                fn ($code, $length) => match ($type) {
                    OneTimePasswordTokenType::RANDOM_STRING => $code->toBeString() && $code->toHaveLength($length),
                    OneTimePasswordTokenType::RANDOM_INT => $code->toBeNumeric() && $code->toHaveLength($length),
                    OneTimePasswordTokenType::ULID => expect(Ulid::isValid($code->value))->toBeTrue(),
                    OneTimePasswordTokenType::UUID => $code->toBeUuid(),
                }
            );
    })
        ->with([
            'string' => [OneTimePasswordTokenType::RANDOM_STRING],
            'integer' => [OneTimePasswordTokenType::RANDOM_INT, 9],
            'ulid' => [OneTimePasswordTokenType::ULID],
            'uuid' => [OneTimePasswordTokenType::UUID],
        ]);

    it('throw error when length is too large for integer type', function () {
        (new TokenGenerator(OneTimePasswordTokenType::RANDOM_INT, 20))
            ->generate();
    })->throws(\RuntimeException::class);
});
