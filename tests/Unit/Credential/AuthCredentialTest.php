<?php

beforeAll(function () {
    \LaravelAuthPro\AuthPro::shouldReceive('getCredentialsMapper')->andReturn([
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface::class,
    ]);

    \LaravelAuthPro\AuthPro::shouldReceive('getAuthProvidersMapper')->andReturn([
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Providers\EmailProvider::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Providers\PhoneProvider::class,
    ]);
});

describe('test auth credential model class', function () {
    it('throw if identifier type not supported', function () {
        $identifier = Mockery::mock(\LaravelAuthPro\Contracts\AuthIdentifierInterface::class)
            ->shouldReceive('getIdentifierType')->andReturn(\LaravelAuthPro\Enums\AuthIdentifierType::EMAIL)
            ->getMock();

        app()->bind(\LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface::class, \LaravelAuthPro\Credentials\PhoneCredential::class);

        (new \LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder())
            ->with('phone')
            ->as($identifier)
            ->by(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->withPayload()
            ->build();
    })->throws(\InvalidArgumentException::class, 'Invalid identifier type [EMAIL] in PhoneCredential');

    it('payload rule must be available according to base interface parents', function () {
        expect(\LaravelAuthPro\Credentials\EmailCredential::class)->toImplement(\LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class);

        expect(\LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class)->toExtend(\LaravelAuthPro\Contracts\Credentials\Base\HasPasswordInterface::class)
            ->and(\LaravelAuthPro\Credentials\EmailCredential::class)->toExtend(\LaravelAuthPro\Contracts\Credentials\Base\HasOneTimePasswordInterface::class);

        expect(\LaravelAuthPro\Credentials\EmailCredential::getPayloadRules())
            ->toHaveKeys(['password', 'code', 'token']);

        expect(\LaravelAuthPro\Credentials\PhoneCredential::getPayloadRules())
            ->toHaveKeys(['password', 'code', 'token']);
    });

    it('return correct builder class', function () {
        expect(\LaravelAuthPro\Credentials\EmailCredential::getBuilder())
            ->toBeInstanceOf(\LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder::class);
    });
});
