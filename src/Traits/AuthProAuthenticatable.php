<?php

namespace LaravelAuthPro\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Model\Builder\AuthenticatableBuilder;

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

    public function getIdentifierMapper(): array
    {
        return [
            'email' => AuthIdentifierType::EMAIL,
            'mobile' => AuthIdentifierType::MOBILE
        ];
    }
}
