<?php

namespace LaravelAuthPro;

use App\Notifications\OneTimePasswordNotification;
use App\Repositories\Auth\Contracts\UserRepositoryInterface;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthExceptionInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthResultInterface;
use LaravelAuthPro\Contracts\AuthServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordServiceInterface;
use LaravelAuthPro\Providers\AuthProvider;
use App\Services\Base\BaseService;
use Illuminate\Notifications\RoutesNotifications;

/**
 * @extends BaseService<UserRepositoryInterface>
 */
class AuthService extends BaseService implements AuthServiceInterface
{
    public function __construct(UserRepositoryInterface $repository,protected readonly OneTimePasswordServiceInterface $oneTimePasswordService)
    {
        parent::__construct($repository);
    }

    public function loginWithCredential(AuthCredentialInterface $credential): AuthResultInterface
    {
        return $this
            ->tryAuthenticate(fn() => AuthProvider::createFromProviderId($credential->getProviderId())->authenticate($credential))
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
}
