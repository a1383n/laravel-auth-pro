<?php

namespace LaravelAuthPro;

use Illuminate\Notifications\RoutesNotifications;
use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthExceptionInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthResultInterface;
use LaravelAuthPro\Contracts\AuthServiceInterface;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use LaravelAuthPro\Contracts\Repositories\UserRepositoryInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordServiceInterface;
use LaravelAuthPro\Notifications\OneTimePasswordNotification;
use LaravelAuthPro\Providers\AuthProvider;

/**
 * @extends BaseService<UserRepositoryInterface>
 */
class AuthService extends BaseService implements AuthServiceInterface
{
    public function __construct(UserRepositoryInterface $repository, protected readonly OneTimePasswordServiceInterface $oneTimePasswordService)
    {
        parent::__construct($repository);
    }

    public function loginWithCredential(AuthCredentialInterface $credential): AuthResultInterface
    {
        return $this
            ->tryAuthenticate(fn () => AuthProvider::createFromProviderId($credential->getProviderId())->authenticate($credential))
            ->as($credential->getIdentifier())
            ->build();
    }

    /**
     * @param callable $closure
     * @return AuthResultBuilder
     */
    private function tryAuthenticate(callable $closure): AuthResultBuilder
    {
        $result = AuthResult::getBuilder();

        try {
            $result->successful($closure());
        } catch (AuthExceptionInterface $e) {
            $result->failed($e);
        }

        return $result;
    }

    public function sendOneTimePassword(AuthIdentifierInterface $identifier): AuthResultInterface
    {
        $otp = $this->oneTimePasswordService->createOneTimePasswordWithIdentifier($identifier);

        if (isset(array_flip(class_uses_recursive($identifier))[RoutesNotifications::class])) {
            /**
             * @var AuthIdentifier $identifier
             */
            $identifier->notify(new OneTimePasswordNotification($otp));
        }

        return AuthResult::getBuilder()
            ->as($identifier)
            ->with([
                'token' => $otp->getToken(),
                'expire_in' => $otp->getValidInterval(),
                'created_at' => $otp->getCreatedAt(),
            ])
            ->build();
    }

    public function getOneTimePasswordSignature(AuthCredentialInterface $phoneCredential, string $ip): AuthResultInterface
    {
        $result = $this->loginWithCredential($phoneCredential);

        if (! $result->isSuccessful()) {
            return $result;
        }

        $user = $result->getUser() ?? throw new \Exception('user cannot be null in result');

        $signature = AuthSignature::getBuilder()
            ->setUserId($user->getId())
            ->setIp($ip)
            ->build();

        return AuthResult::getBuilder()
            ->as($result->getIdentifier())
            ->with([
                'signature' => $signature,
            ])
            ->successful($user)
            ->build();
    }

    public function verifyOneTimePassword(PhoneCredentialInterface $phoneCredential, bool $dry = false): AuthResultInterface
    {
        $result = $this->oneTimePasswordService->verifyOneTimePassword($phoneCredential->getIdentifier(), $phoneCredential, $dry);
        if (! $result->isSuccessful()) {
            return AuthResult::getBuilder()
                ->failed(new AuthException($result->getError()->value))
                ->build();
        }

        return AuthResult::getBuilder()
            ->successful($this->repository->getUserByIdentifier($phoneCredential->getIdentifier()))
            ->build();
    }

    public function verifyOneTimePasswordSignature(AuthSignatureInterface $signature): AuthResultInterface
    {
        $result = $this->oneTimePasswordService->verifyOneTimePasswordSignature($signature);
        if (! $result->isSuccessful()) {
            return $result;
        }

        return AuthResult::getBuilder()
            ->successful($this->repository->getUserById($signature->getUserId()))
            ->build();
    }
}
