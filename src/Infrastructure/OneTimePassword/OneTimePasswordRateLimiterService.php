<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword;

use Illuminate\Container\Container;
use Illuminate\Container\ContextualBindingBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use LaravelAuthPro\Base\BaseService;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseRepositoryInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordLimiterInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordRateLimiterServiceInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\OneTimePasswordFailedAttemptsLimiter;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\OneTimePasswordIdentifierLimiter;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\OneTimePasswordIpAddressLimiter;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

/**
 * @extends BaseService<BaseRepositoryInterface>
 */
class OneTimePasswordRateLimiterService extends BaseService implements OneTimePasswordRateLimiterServiceInterface
{
    /**
     * @var array<class-string>
     */
    private const REQUEST_LIMITERS = [
        OneTimePasswordIpAddressLimiter::class,
        OneTimePasswordIdentifierLimiter::class,
    ];

    private const VERIFY_LIMITER = [
        OneTimePasswordFailedAttemptsLimiter::class
    ];

    private const FAILED_LIMITER = [
        OneTimePasswordFailedAttemptsLimiter::class
    ];

    /**
     * @var array<int, object>
     */
    private array $limiterInstances = [];

    public function __construct(private readonly Container $container)
    {
        parent::__construct();
    }

    /**
     * @param class-string[] $limitersConcretes
     * @param callable<class-string, ContextualBindingBuilder>[] $contextualBindingClosures
     * @param callable<OneTimePasswordLimiterInterface> $limiterClosure
     * @return bool
     */
    protected function passes(array $limitersConcretes, array $contextualBindingClosures, callable $limiterClosure): bool
    {
        $instances = $this->getLimiterInstances($limitersConcretes, $contextualBindingClosures);

        foreach ($instances as $limiterInstance) {
            $result = $limiterClosure($limiterInstance) ?? true;

            if (! $result) {
                return false;
            }
        }

        return true;
    }

    public function passesRequest(AuthIdentifierInterface $identifier): bool
    {
        return $this->passes(
            self::REQUEST_LIMITERS,
            [
                fn(string $concrete, ContextualBindingBuilder $builder) => $builder->needs(AuthIdentifierInterface::class)->give(fn () => $identifier),
            ],
            fn(OneTimePasswordLimiterInterface $limiter) => method_exists($limiter, 'pass') ? $limiter->pass($identifier) : false
        );
    }

    public function passesVerify(AuthIdentifierInterface $identifier, OneTimePasswordEntityInterface $entity): bool
    {
        return $this->passes(
            self::VERIFY_LIMITER,
            [
                fn(string $concrete, ContextualBindingBuilder $builder) => $builder->needs(AuthIdentifierInterface::class)->give(fn () => $identifier),
                fn(string $concrete, ContextualBindingBuilder $builder) => $builder->needs(OneTimePasswordEntityInterface::class)->give(fn () => $entity)
            ],
            fn(OneTimePasswordLimiterInterface $limiter) => method_exists($limiter, 'pass') ? $limiter->pass($identifier) : false
        );
    }

    public function passesFailed(AuthIdentifierInterface $identifier, OneTimePasswordEntityInterface $entity): bool
    {
        return $this->passes(
            self::FAILED_LIMITER,
            [
                fn(string $concrete, ContextualBindingBuilder $builder) => $builder->needs(AuthIdentifierInterface::class)->give(fn () => $identifier),
                fn(string $concrete, ContextualBindingBuilder $builder) => $builder->needs(OneTimePasswordEntityInterface::class)->give(fn () => $entity)
            ],
            function (OneTimePasswordLimiterInterface $limiter) {
                if (method_exists($limiter, 'hit')) {
                    $limiter->hit();
                }
            }
        );
    }

    /**
     * @param class-string[] $concretes
     * @param callable<class-string, ContextualBindingBuilder>[] $contextualBindingClosures
     * @return OneTimePasswordLimiterInterface[]
     * @throws BindingResolutionException
     */
    private function getLimiterInstances(array $concretes, array $contextualBindingClosures): array
    {
        $instances = [];

        foreach ($concretes as $concrete) {
            if (isset($this->limiterInstances[$concrete]))
                continue;

            foreach ($contextualBindingClosures as $closure) {
                $closure($concrete, $this->container->when($concrete));
            }

            $instances[] = $this->container->make($concrete);
        }

        $this->limiterInstances += $instances;

        return $instances;
    }
}
