<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword;

use Illuminate\Contracts\Foundation\Application;
use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordRateLimiterServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordResultInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifierServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Model\OneTimePasswordVerifyResult;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordRepositoryInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordVerifierRepositoryInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\OneTimePasswordRepository;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\OneTimePasswordVerifierRepository;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Model\OneTimePasswordEntity;

/**
 * @extends BaseService<OneTimePasswordRepositoryInterface>
 */
class OneTimePasswordService extends BaseService implements OneTimePasswordServiceInterface
{
    public function __construct(OneTimePasswordRepositoryInterface $repository, protected readonly OneTimePasswordRateLimiterServiceInterface $rateLimiterService, protected readonly OneTimePasswordVerifierServiceInterface $verifierService)
    {
        parent::__construct($repository);
    }

    public static function register(Application $app): void
    {
        $app->bind(OneTimePasswordRepositoryInterface::class, OneTimePasswordRepository::class);
        $app->bind(OneTimePasswordVerifierRepositoryInterface::class, OneTimePasswordVerifierRepository::class);

        $app->bind(OneTimePasswordRateLimiterServiceInterface::class, OneTimePasswordRateLimiterService::class);
        $app->bind(OneTimePasswordVerifierServiceInterface::class, OneTimePasswordVerifierService::class);
        $app->bind(OneTimePasswordServiceInterface::class, OneTimePasswordService::class);
    }

    public function createOneTimePasswordWithIdentifier(AuthIdentifierInterface $identifier): OneTimePasswordEntityInterface
    {
        if (!$this->rateLimiterService->pass($identifier)) {
            //TODO: Returning result interface may be better approach
            throw new AuthException(OneTimePasswordError::RATE_LIMIT_EXCEEDED->value, 429);
        }

        $otp = OneTimePasswordEntity::getBuilder()
            ->as($identifier)
            ->build();

        $this->repository->createOneTimePasswordWithIdentifier($otp);

        return $otp;
    }

    public function verifyOneTimePassword(AuthIdentifierInterface $identifier, string $token, string $code): OneTimePasswordResultInterface
    {
        $otp = $this->repository->getOneTimePasswordWithIdentifierAndToken($identifier, $token);

        if ($otp === null) {
            return OneTimePasswordVerifyResult::getBuilder()
                ->failed(OneTimePasswordVerifyError::TOKEN_NOT_FOUND)
                ->build();
        }

        $result = $this->verifierService->verify($otp, $code);
        if ($result->isSuccessful()) {
            $this->repository->removeOneTimePassword($otp);
        }

        return $result;
    }
}
