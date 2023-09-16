<?php

namespace LaravelAuthPro\SignInMethods;

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Contracts\Credentials\GoogleCredentialInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;

class OAuthSignInMethod implements AuthSignInMethodInterface
{
    /**
     * @param AuthenticatableInterface $user
     * @param GoogleCredentialInterface $credential
     * @return AuthenticatableInterface
     * @throws AuthException
     */
    public function __invoke(AuthenticatableInterface $user, GoogleCredentialInterface|AuthCredentialInterface $credential): AuthenticatableInterface
    {
        try {
            /**
             * @var GoogleProvider $socialite
             */
            $socialite = Socialite::driver($credential->getProviderId());

            $oauthUser = $socialite->userFromToken($credential->getIdToken());

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
