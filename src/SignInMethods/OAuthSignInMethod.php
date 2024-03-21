<?php

namespace LaravelAuthPro\SignInMethods;

use Laravel\Socialite\Facades\Socialite;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Contracts\Credentials\OAuthCredentialInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;

class OAuthSignInMethod implements AuthSignInMethodInterface
{
    /**
     * @param AuthenticatableInterface $user
     * @param OAuthCredentialInterface|AuthCredentialInterface $credential
     * @return AuthenticatableInterface
     * @throws AuthException
     */
    public function __invoke(AuthenticatableInterface $user, OAuthCredentialInterface|AuthCredentialInterface $credential): AuthenticatableInterface
    {
        try {
            $oauthUser = Socialite::driver($credential->getDriver())->userFromToken($credential->getIdToken());

            dump($user, $oauthUser);

            throw new \Exception('not implemented');
        } catch (\Exception $e) {
            throw new AuthException('auth.oauth_error', 400, ['e' => $e]);
        }
    }

    /**
     * @inheritDoc
     */
    public function getUserRequiredColumns(): array
    {
        return [
            'email',
        ];
    }
}
