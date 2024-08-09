<?php

namespace LaravelAuthPro\Providers;

use Laravel\Socialite\Facades\Socialite;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\Providers\OAuthProviderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;

class OAuthProvider extends AuthProvider implements OAuthProviderInterface
{
    public const ID = 'oauth';
    public const TYPE = AuthProviderType::OAUTH;
    public const IDENTIFIER_TYPE = AuthIdentifierType::EMAIL;
    public const SUPPORTED_SIGN_IN_METHODS = [
        AuthProviderSignInMethod::OAUTH,
    ];

    public function createUserWithIdToken(string $driver, string $idToken): AuthenticatableInterface
    {
        $user = Socialite::driver($driver)
            ->userFromToken($idToken);

        $authenticatable = $this->createAuthenticatable($user->getEmail());

        $authenticatable->authProviders()
            ->create([
                'provider_type' => static::TYPE,
                'provider_id' => static::ID . '.' . $driver,
                'payload' => [
                    'id' => $user->getId(),
                    'extra' => $user->getRaw(),
                ],
            ]);

        return $authenticatable;
    }
}
