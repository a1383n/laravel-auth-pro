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
     * @param AuthIdentifierInterface $identifier
     * @return Builder<AuthenticatableInterface>
     */
    public static function whereIdentifier(AuthIdentifierInterface $identifier): Builder;

    public function getPassword(): ?string;
}
