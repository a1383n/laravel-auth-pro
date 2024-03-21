<?php

namespace LaravelAuthPro\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

/**
 * @mixin Model
 */
interface AuthenticatableInterface extends HasBuilderInterface
{
    /**
     * @param Builder<Model> $builder
     * @param AuthIdentifierInterface $identifier
     * @return Builder<Model>
     */
    public function scopeWhereIdentifier(Builder $builder, AuthIdentifierInterface $identifier): Builder;

    public static function getPasswordKey(): string;

    public function getPassword(): ?string;

    public function getId(): string;

    /**
     * Map {@link AuthIdentifierType} to column name exists in database
     * Ex.
     * <code>
     *     return [
     *              'email' => AuthIdentifierType::EMAIL,
     *              'mobile' => AuthIdentifierType::MOBILE
     * ];
     * </code>
     * @return array
     */
    public static function getIdentifierMapper(): array;

    public function authProviders(): HasMany;
}
