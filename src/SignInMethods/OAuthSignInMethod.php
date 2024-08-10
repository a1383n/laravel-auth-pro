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
     * @throws AuthException
     */
    public function __invoke(AuthCredentialInterface $credential, ?AuthenticatableInterface $user = null): void
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
     * {@inheritDoc}
     */
    public function getUserRequiredColumns(): array
    {
        return [
            'email',
        ];
    }
}
