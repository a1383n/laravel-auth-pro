<?php

use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordCodeType;
use LaravelAuthPro\Infrastructure\OneTimePassword\Support\CodeGenerator;

test('code generator generates random code', function (OneTimePasswordCodeType $type) {
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
