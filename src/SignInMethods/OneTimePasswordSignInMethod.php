<?php

namespace LaravelAuthPro\SignInMethods;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthExceptionInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifyResultInterface;

class OneTimePasswordSignInMethod implements AuthSignInMethodInterface
{
    public function __construct(protected readonly OneTimePasswordServiceInterface $oneTimePasswordService)
    {
        //
    }

    /**
     * @param AuthenticatableInterface $user
     * @param PhoneCredentialInterface $credential
     * @return AuthenticatableInterface
     * @throws AuthException
     */
    public function __invoke(AuthenticatableInterface $user, PhoneCredentialInterface|AuthCredentialInterface $credential): AuthenticatableInterface
    {
        if ($credential->getOneTimePassword() === null) {
            throw new \InvalidArgumentException('$code is null in $credential');
        }

        $result = $this->oneTimePasswordService->verifyOneTimePassword($credential->getIdentifier(), $credential);

        if (! $result->isSuccessful()) {
            if ($result instanceof OneTimePasswordVerifyResultInterface) {
                throw new AuthException($result->getVerifierError()?->value, 400, $result->getPayload());
            } else {
                $error = $result->getError();

                if ($error instanceof AuthExceptionInterface) {
                    $error = $error->getErrorMessage();
                } elseif ($error instanceof \Exception) {
                    $error = $error->getMessage();
                } else {
                    $error = json_encode($error);
                }

                /**
                 * @var string|null $error
                 */
                throw new AuthException($error, 400, $result->getPayload());
            }
        }

        return $user;
    }

    public function getUserRequiredColumns(): array
    {
        return [
            'id',
            'mobile',
        ];
    }
}
