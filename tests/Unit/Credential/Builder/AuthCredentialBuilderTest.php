<?php

beforeAll(function () {
    \LaravelAuthPro\AuthPro::shouldReceive('getCredentialsMapper')->andReturn([
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface::class
    ]);

    \LaravelAuthPro\AuthPro::shouldReceive('getAuthProvidersMapper')->andReturn([
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Providers\EmailProvider::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Providers\PhoneProvider::class
    ]);
});

describe('test auth credential builder', function () {
    it('build email credential class', function () {
        $authIdentifier = Mockery::mock(\LaravelAuthPro\Contracts\AuthIdentifierInterface::class)
            ->shouldReceive('getIdentifierType')->andReturn(\LaravelAuthPro\Enums\AuthIdentifierType::EMAIL)
            ->getMock();

        app()->bind(\LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class, \LaravelAuthPro\Credentials\EmailCredential::class);

        $model = (new \LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder())
            ->with('email')
            ->by(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->as($authIdentifier)
            ->withPayload(['password' => 'foo', 'token' => 'bar', 'code' => 'test'])
            ->build();

        expect($model)->toBeInstanceOf(\LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class)
            ->and($model->getIdentifier())->toBe($authIdentifier)
            ->and($model->getProviderId())->toBe('email')
            ->and($model->getSignInMethod())->toBe(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->and($model->getPassword())->toBe('foo')
            ->and($model->getOneTimePasswordToken())->toBe('bar')
            ->and($model->getOneTimePassword())->toBe('test');
    });

    it('build phone credential class', function () {
        $authIdentifier = Mockery::mock(\LaravelAuthPro\Contracts\AuthIdentifierInterface::class)
            ->shouldReceive('getIdentifierType')->andReturn(\LaravelAuthPro\Enums\AuthIdentifierType::MOBILE)
            ->getMock();

        app()->bind(\LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface::class, \LaravelAuthPro\Credentials\PhoneCredential::class);

        $model = (new \LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder())
            ->with('phone')
            ->by(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->as($authIdentifier)
            ->withPayload(['password' => 'foo', 'token' => 'bar', 'code' => 'test'])
            ->build();

        expect($model)->toBeInstanceOf(\LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface::class)
            ->and($model->getIdentifier())->toBe($authIdentifier)
            ->and($model->getProviderId())->toBe('phone')
            ->and($model->getSignInMethod())->toBe(\LaravelAuthPro\Enums\AuthProviderSignInMethod::PASSWORD)
            ->and($model->getPassword())->toBe('foo')
            ->and($model->getOneTimePasswordToken())->toBe('bar')
            ->and($model->getOneTimePassword())->toBe('test');
    });

    it('throw error when provider is null', function () {
        (new \LaravelAuthPro\Credentials\Builder\AuthCredentialBuilder())
            ->build();
    })->throws(\InvalidArgumentException::class);
});
