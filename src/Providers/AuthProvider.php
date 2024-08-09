<?php

namespace LaravelAuthPro\Providers;

use Illuminate\Container\Container;
use LaravelAuthPro\AuthIdentifier;
use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use LaravelAuthPro\Contracts\Repositories\UserRepositoryInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;
use LaravelAuthPro\Model\Builder\AuthProviderBuilder;
use LaravelAuthPro\Traits\HasBuilder;

abstract class AuthProvider implements AuthProviderInterface, HasBuilderInterface
{
    use HasBuilder;

    /**
     * @type string|null
     */
    public const ID = null;

    /**
     * @type AuthProviderType|null
     */
    public const TYPE = null;

    /**
     * @type AuthIdentifierType|null
     */
    public const IDENTIFIER_TYPE = null;

    /**
     * @type AuthProviderSignInMethod[]|null
     */
    public const SUPPORTED_SIGN_IN_METHODS = [];

    /**
     * @type array<string, class-string<AuthSignInMethodInterface>>
     */
    protected const SIGN_IN_METHODS = [];

    public function __construct(private readonly UserRepositoryInterface $userRepository, protected readonly ?string $authenticatableModel = null)
    {
        //
    }

    protected static function getBuilderClass(): string
    {
        return AuthProviderBuilder::class;
    }

    protected function getRepository(): UserRepositoryInterface
    {
        return $this->userRepository
            ->setUserModelClass($this->getAuthenticatableModel());
    }

    public function createIdentifier(string $value): AuthIdentifierInterface
    {
        return new AuthIdentifier(static::IDENTIFIER_TYPE, $value);
    }

    protected function getAuthenticatableModel(): string
    {
        return $this->authenticatableModel ?? AuthPro::getDefaultAuthenticatableModel();
    }

    protected function getSignInMethodClass(AuthProviderSignInMethod $signInMethod): string
    {
        return (static::SIGN_IN_METHODS + AuthPro::getDefaultSignInMethodsMapper())[$signInMethod->value] ?? throw new \InvalidArgumentException("SignInMethod $signInMethod->value is not defined in mapper");
    }

    protected function createAuthenticatable(string $identifierValue, ?callable $beforeBuildClosure = null): AuthenticatableInterface
    {
        $builder = $this->getAuthenticatableModel()::getBuilder()
            ->as($identifier = $this->createIdentifier($identifierValue));

        if ($beforeBuildClosure !== null) {
            $beforeBuildClosure($builder);
        }

        if (! $this->getRepository()->createByAuthenticatable($identifier, $authenticatable = $builder->build())) {
            throw new \Exception('Failed to save the user to the database');
        }

        return $authenticatable;
    }

    public function authenticate(AuthCredentialInterface $credential): AuthenticatableInterface
    {
        $signInMethodClass = $this->getSignInMethodClass($credential->getSignInMethod());

        if (! $this->getRepository()->isUserExist($credential->getIdentifier())) {
            throw new AuthException('user_not_found');
        }

        /**
         * @var AuthSignInMethodInterface $signInMethod
         */
        $signInMethod = Container::getInstance()->make($signInMethodClass);

        /**
         * @var AuthenticatableInterface $user
         */
        $user = $this->getRepository()->getUserByIdentifier($credential->getIdentifier(), $signInMethod->getUserRequiredColumns());

        return $signInMethod($user, $credential);
    }
}
