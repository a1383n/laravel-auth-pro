<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword;

use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifierServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifyResultInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Model\OneTimePasswordVerifyResult;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordVerifierRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use Illuminate\Support\Facades\Hash;

/**
 * @extends BaseService<OneTimePasswordVerifierRepositoryInterface>
 */
class OneTimePasswordVerifierService extends BaseService implements OneTimePasswordVerifierServiceInterface
{
    protected const MAX_FAILED_ATTEMPTS = 3;

    public function __construct(OneTimePasswordVerifierRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function verify(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): OneTimePasswordVerifyResultInterface
    {
        $result = OneTimePasswordVerifyResult::getBuilder();

        $failedAttempts = $this->repository->getFailedAttemptsCount($oneTimePasswordEntity);
        if ($failedAttempts >= self::MAX_FAILED_ATTEMPTS) {
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
