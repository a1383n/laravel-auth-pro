<?php

beforeAll(function () {
    app()->flush();

    \LaravelAuthPro\AuthPro::shouldReceive('getCredentialsMapper')->andReturn([
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface::class,
    ]);

    \LaravelAuthPro\AuthPro::shouldReceive('getAuthProvidersMapper')->andReturn([
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Providers\EmailProvider::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Providers\PhoneProvider::class,
    ]);
});

describe('test email credential', function () {
    it('have correct email according to identifier', function () {
        $identifier = Mockery::mock(\LaravelAuthPro\Contracts\AuthIdentifierInterface::class)
            ->shouldReceive('getIdentifierValue')->andReturn('someone@example.com')
            ->shouldReceive('getIdentifierType')->andReturn(\LaravelAuthPro\Enums\AuthIdentifierType::EMAIL)
            ->getMock();

        app()->bind(\LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class, \LaravelAuthPro\Credentials\EmailCredential::class);

        $credential = (new \LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder())
            ->with('email')
            ->as($identifier)
            ->by(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->withPayload()
            ->build();

        expect($credential)->toBeInstanceOf(\LaravelAuthPro\Credentials\EmailCredential::class)
            ->and($credential->getEmail())->toBe('someone@example.com');
    });
});
