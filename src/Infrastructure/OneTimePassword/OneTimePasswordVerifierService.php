<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword;

use Illuminate\Support\Facades\Hash;
use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifierServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifyResultInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Model\OneTimePasswordVerifyResult;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordVerifierRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

/**
 * @extends BaseService<OneTimePasswordVerifierRepositoryInterface>
 */
class OneTimePasswordVerifierService extends BaseService implements OneTimePasswordVerifierServiceInterface
{
    protected readonly int $maxFailedAttempts;

    public function __construct(OneTimePasswordVerifierRepositoryInterface $repository)
    {
        parent::__construct($repository);

        /**
         * @phpstan-ignore-next-line
         */
        $this->maxFailedAttempts = config('auth_pro.one_time_password.max_attempts', 3);
    }

    public function verify(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): OneTimePasswordVerifyResultInterface
    {
        $result = OneTimePasswordVerifyResult::getBuilder();

        $failedAttempts = $this->repository->getFailedAttemptsCount($oneTimePasswordEntity);
        if ($failedAttempts >= $this->maxFailedAttempts) {
            return $result
                ->failed(OneTimePasswordVerifyError::TOO_MANY_FAILED_ATTEMPTS)
                ->build();
        }

        if ($this->check($oneTimePasswordEntity, $code)) {
            return $result
                ->successful()
                ->build();
        } else {
            $this->repository->incrementFailAttemptsCount($oneTimePasswordEntity);

            return $result
                ->failed(OneTimePasswordVerifyError::INVALID_CODE)
                ->build();
        }
    }

    public function check(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): bool
    {
        return Hash::check($code, $oneTimePasswordEntity->getCode());
    }
}
