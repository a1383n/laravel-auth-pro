<?php

namespace LaravelAuthPro\Traits;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Model\Builder\AuthenticatableBuilder;
use LaravelAuthPro\Model\UserAuthProvider;

/**
 * @mixin AuthenticatableInterface
 * @mixin Model
 */
trait AuthProAuthenticatable
{
    use HasBuilder;

    protected static function getBuilderClass(): string
    {
        return AuthenticatableBuilder::class;
    }

    public static function getBuilder(): EntityBuilderInterface
    {
        return Container::getInstance()
            ->make(static::getBuilderClass(), ['authenticatableModel' => static::class]);
    }

    public function scopeWhereIdentifier(Builder $builder, AuthIdentifierInterface $identifier): Builder
    {
        return $builder->where($identifier->getIdentifierType()->value, $identifier->getIdentifierValue());
    }

    public function getPassword(): ?string
    {
        return $this->getAttribute('password');
    }

    public static function getPasswordKey(): string
    {
        return 'password';
    }

    public function getId(): string
    {
        return $this->getKey();
    }

    public static function getIdentifierMapper(): array
    {
        return [
            'email' => AuthIdentifierType::EMAIL,
            'mobile' => AuthIdentifierType::MOBILE,
        ];
    }

    public function authProviders(): HasMany
    {
        return $this->hasMany(UserAuthProvider::class, 'user_id');
    }
}
