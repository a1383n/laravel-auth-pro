<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifierServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordVerifyResultInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordVerifyError;
use LaravelAuthPro\Infrastructure\OneTimePassword\Model\OneTimePasswordVerifyResult;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

/**
 * @extends BaseService
 */
class OneTimePasswordVerifierService extends BaseService implements OneTimePasswordVerifierServiceInterface
{
    public function verify(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): OneTimePasswordVerifyResultInterface
    {
        $result = OneTimePasswordVerifyResult::getBuilder();

        if ($this->tooManyAttempts($oneTimePasswordEntity)) {
            return $result
                ->failed(OneTimePasswordVerifyError::TOO_MANY_FAILED_ATTEMPTS)
                ->build();
        } elseif ($this->check($oneTimePasswordEntity, $code)) {
            return $result
                ->successful()
                ->build();
        } else {
            $this->incrementFailAttemptsCount($oneTimePasswordEntity);

            return $result
                ->failed(OneTimePasswordVerifyError::INVALID_CODE)
                ->build();
        }
    }

    public function check(OneTimePasswordEntityInterface $oneTimePasswordEntity, string $code): bool
    {
        return Hash::check($code, $oneTimePasswordEntity->getCode());
    }

    protected function tooManyAttempts(OneTimePasswordEntityInterface $oneTimePasswordEntity): bool
    {
        return RateLimiter::tooManyAttempts(md5('auth_pro_otp_failed_attempts'.$oneTimePasswordEntity->getIdentifier()->getIdentifierValue()), config('auth_pro.one_time_password.max_attempts', 3));
    }

    protected function incrementFailAttemptsCount(OneTimePasswordEntityInterface $oneTimePasswordEntity): int
    {
        return RateLimiter::hit(md5('auth_pro_otp_failed_attempts'.$oneTimePasswordEntity->getIdentifier()->getIdentifierValue()), $oneTimePasswordEntity->getValidInterval()->seconds);
    }
}
