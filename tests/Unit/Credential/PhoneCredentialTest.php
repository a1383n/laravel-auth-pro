<?php

beforeAll(function () {
    //TODO: Review this later
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

describe('test phone credential', function () {
    it('have correct phone according to identifier', function () {
        $authIdentifier = Mockery::mock(\LaravelAuthPro\Contracts\AuthIdentifierInterface::class)
            ->shouldReceive('getIdentifierValue')->andReturn('111222333')
            ->shouldReceive('getIdentifierType')->andReturn(\LaravelAuthPro\Enums\AuthIdentifierType::MOBILE)
            ->getMock();

        app()->bind(\LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface::class, \LaravelAuthPro\Credentials\PhoneCredential::class);

        $credential = (new \LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder())
            ->with('phone')
            ->by(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->as($authIdentifier)
            ->withPayload(['password' => 'foo', 'token' => 'bar', 'code' => 'test'])
            ->build();

        expect($credential)->toBeInstanceOf(\LaravelAuthPro\Credentials\PhoneCredential::class)
            ->and($credential->getPhone())->toBe('111222333');
    });

    it('load signature from encrypted string', function () {
        $signature = new \LaravelAuthPro\AuthSignature('id', 'ip', 'user_id', now());

        $authIdentifier = Mockery::mock(\LaravelAuthPro\Contracts\AuthIdentifierInterface::class)
            ->shouldReceive('getIdentifierValue')->andReturn('111222333')
            ->shouldReceive('getIdentifierType')->andReturn(\LaravelAuthPro\Enums\AuthIdentifierType::MOBILE)
            ->getMock();


        \Illuminate\Support\Facades\Crypt::shouldReceive('encrypt')->andReturn('encrypted_string');
        \Illuminate\Support\Facades\Crypt::shouldReceive('decrypt')->andReturn($signature->toArray());

        $credential = (new \LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder())
            ->with('phone')
            ->by(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->as($authIdentifier)
            ->withPayload(['signature' => $signature->__toString()])
            ->build();

        expect($credential->getSignature()->toArray())->toBe($signature->toArray());
    });
});
