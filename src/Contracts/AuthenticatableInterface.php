<?php

namespace LaravelAuthPro\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
interface AuthenticatableInterface
{
    /**
     * @param Builder<Model> $builder
     * @param AuthIdentifierInterface $identifier
     * @return Builder<Model>
     */
    public function scopeWhereIdentifier(Builder $builder, AuthIdentifierInterface $identifier): Builder;

    /**
     * @param AuthIdentifierInterface $identifier
     * @return Builder<Model>
     */
    public static function whereIdentifier(AuthIdentifierInterface $identifier): Builder;

    public function getPassword(): ?string;

    public function getId(): string;
}
