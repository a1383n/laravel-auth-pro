<?php

namespace LaravelAuthPro\Providers;

use Illuminate\Container\Container;
use LaravelAuthPro\AuthProServiceProvider;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use LaravelAuthPro\Contracts\Repositories\UserRepositoryInterface;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;

abstract class AuthProvider implements AuthProviderInterface
{
    /**
     * @type string|null
     */
    public const ID = null;

    /**
     * @type AuthProviderType|null
     */
    public const TYPE = null;

    /**
     * @type AuthProviderSignInMethod[]|null
     */
    public const SUPPORTED_SIGN_IN_METHODS = null;

    /**
     * @type array<string, class-string<AuthSignInMethodInterface>>
     */
    protected const SIGN_IN_METHODS = [];

    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        //
    }

    public static function createFromProviderId(string $id): AuthProviderInterface
    {
        return Container::getInstance()
            ->make(sprintf(AuthProServiceProvider::CONTAINER_ALIAS_AUTH_PROVIDER_TEMPLATE, $id));
    }

    public function getProviderId(): string
    {
        if (self::ID === null) {
            throw new \InvalidArgumentException('TYPE const is null');
        }

        /**
         * @phpstan-ignore-next-line
         */
        return self::ID;
    }

    public function getProviderType(): AuthProviderType
    {
        if (self::TYPE === null) {
            throw new \InvalidArgumentException('TYPE const is null');
        }

        /**
         * @phpstan-ignore-next-line
         */
        return self::TYPE;
    }

    public function getProviderSignInMethods(): array
    {
        if (self::SUPPORTED_SIGN_IN_METHODS === null) {
            throw new \InvalidArgumentException('TYPE const is null');
        }

        /**
         * @phpstan-ignore-next-line
         */
        return self::SUPPORTED_SIGN_IN_METHODS;
    }

    public function authenticate(AuthCredentialInterface $credential): AuthenticatableInterface
    {
        if (empty($signInMethodClass = static::SIGN_IN_METHODS[$signInMethodEnumValue = $credential->getSignInMethod()->value] ?? null)) {
            throw new \InvalidArgumentException(sprintf('sign in method %s not defined', $signInMethodEnumValue));
        }

        if (! $this->userRepository->isUserExist($credential->getIdentifier())) {
            throw new AuthException('user_not_found');
        }

        /**
         * @var AuthSignInMethodInterface $signInMethod
         */
        $signInMethod = Container::getInstance()->make($signInMethodClass);

        /**
         * @var AuthenticatableInterface $user
         */
        $user = $this->userRepository->getUserByIdentifier($credential->getIdentifier(), $signInMethod->getUserRequiredColumns());

        return $signInMethod->__invoke($user, $credential);
    }
}
