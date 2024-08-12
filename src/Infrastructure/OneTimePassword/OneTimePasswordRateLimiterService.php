<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword;

use Illuminate\Container\Container;
use Illuminate\Redis\Connections\Connection;
use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseRepositoryInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordRateLimiterServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\OneTimePasswordIdentifierLimiter;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\OneTimePasswordIpAddressLimiter;

/**
 * @extends BaseService<BaseRepositoryInterface>
 */
class OneTimePasswordRateLimiterService extends BaseService implements OneTimePasswordRateLimiterServiceInterface
{
    /**
     * @var array<class-string>
     */
    private readonly array $limiters;

    /**
     * @var array<int, object>
     */
    private array $limiterInstances = [];

    public function __construct(protected readonly Connection $connection, private readonly Container $container)
    {
        parent::__construct();

        $this->limiters = array_keys(config('auth_pro.one_time_password.rate_limit', [OneTimePasswordIpAddressLimiter::class, OneTimePasswordIdentifierLimiter::class]));
    }

    public function pass(AuthIdentifierInterface $identifier): bool
    {
        if (empty($this->limiterInstances)) {
            $this->createLimiterInstance($identifier);
        }

        foreach ($this->limiterInstances as $limiterInstance) {
            $result = method_exists($limiterInstance, 'pass') ? $limiterInstance->pass($identifier) : false;

            if (! $result) {
                return false;
            }
        }

        return true;
    }

    private function createLimiterInstance(AuthIdentifierInterface $identifier): void
    {
        foreach ($this->limiters as $limiterClass) {
            $this->container
                ->when($limiterClass)
                ->needs(AuthIdentifierInterface::class)
                ->give(fn () => $identifier);

            $this->limiterInstances[] = $this->container->make($limiterClass);
        }
    }
}
