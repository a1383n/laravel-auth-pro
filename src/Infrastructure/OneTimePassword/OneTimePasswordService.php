<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;
use LaravelAuthPro\AuthResult;
use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthResultInterface;
use LaravelAuthPro\Contracts\AuthSignatureInterface;
use LaravelAuthPro\Contracts\Credentials\PhoneCredentialInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordRateLimiterServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordResultInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifierServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Model\OneTimePasswordVerifyResult;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordRepositoryInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\OneTimePasswordRepository;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Model\OneTimePasswordEntity;

/**
 * @extends BaseService<OneTimePasswordRepositoryInterface>
 */
class OneTimePasswordService extends BaseService implements OneTimePasswordServiceInterface
{
    public function __construct(OneTimePasswordRepositoryInterface $repository, protected readonly OneTimePasswordVerifierServiceInterface $verifierService)
    {
        parent::__construct($repository);
    }

    public static function register(Application $app): void
    {
        $app->bind(OneTimePasswordRepositoryInterface::class, OneTimePasswordRepository::class);

        $app->bind(OneTimePasswordVerifierServiceInterface::class, OneTimePasswordVerifierService::class);
        $app->bind(OneTimePasswordServiceInterface::class, OneTimePasswordService::class);
    }

    public function createOneTimePasswordWithIdentifier(AuthIdentifierInterface $identifier): OneTimePasswordEntityInterface
    {
        $limiters = RateLimiter::limiter($limiterScopeKey = 'auth_pro_otp');
        $limiters = collect(Arr::wrap($limiters !== null ? $limiters(request(), $identifier) : []))
            ->map(function (Limit $limit) use ($limiterScopeKey) {
                $limit->key = md5($limiterScopeKey . $limit->key);

                return $limit;
            });

        if ($limiter = $limiters->first(fn(Limit $limiter) => RateLimiter::tooManyAttempts($limiter->key, $limiter->maxAttempts))) {
            throw new AuthException(OneTimePasswordError::RATE_LIMIT_EXCEEDED->value, 429, ['try_again_in' => now()->addSeconds(RateLimiter::availableIn($limiter->key))->diffForHumans()]);
        }

        return tap($this->createOneTimePasswordEntity($identifier), function () use ($limiters) {
            $limiters->each(fn(Limit $limiter) => RateLimiter::hit($limiter->key));
        });
    }

    protected function createOneTimePasswordEntity(AuthIdentifierInterface $identifier): OneTimePasswordEntityInterface
    {
        $otp = OneTimePasswordEntity::getBuilder()
            ->as($identifier)
            ->build();

        if (! $this->repository->createOneTimePasswordWithIdentifier($otp)) {
            /**
             * @var OneTimePasswordEntity $otp
             */
            $otp = $this->repository->getOneTimePasswordWithIdentifierAndToken($identifier);

            throw new AuthException(OneTimePasswordError::CONFLICT->value, 409, ['try_again_in' => $otp->getCreatedAt()->add($otp->getValidInterval())->diffForHumans()]);
        }

        return $otp;
    }

    public function verifyOneTimePassword(AuthIdentifierInterface $identifier, PhoneCredentialInterface $credential, bool $dry = false): OneTimePasswordResultInterface
    {
        $otp = $this->repository->getOneTimePasswordWithIdentifierAndToken($identifier, $credential->getOneTimePasswordToken());

        if ($otp === null) {
            return OneTimePasswordVerifyResult::getBuilder()
                ->failed(OneTimePasswordVerifyError::TOKEN_NOT_FOUND)
                ->build();
        }

        $result = $this->verifierService->verify($otp, $credential->getOneTimePassword() ?? throw new \Exception('code is null'));
        if ($result->isSuccessful() && !$dry) {
            $this->repository->removeOneTimePassword($otp);
        }

        return $result;
    }

    public function verifyOneTimePasswordSignature(AuthSignatureInterface $signature): AuthResultInterface
    {
        /**
         * @var int $signatureExpireSeconds
         */
        $signatureExpireSeconds = config('auth_pro.one_time_password.signature.expiry', 60);

        if (now()->diffInSeconds($signature->getTimestamp()) > $signatureExpireSeconds) {
            return AuthResult::getBuilder()
                ->failed(new AuthException('signature_expired'))
                ->build();
        }

        if ($this->repository->isSignatureUsed($signature->getId())) {
            return AuthResult::getBuilder()
                ->failed(new AuthException('signature_already_used'))
                ->build();
        }

        $this->repository->markSignatureAsUsed($signature->getId(), $signatureExpireSeconds);

        return AuthResult::getBuilder()
            ->successful()
            ->build();
    }
}
