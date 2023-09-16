<?php

namespace LaravelAuthPro;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAuthProvidersMapper()
 */
class AuthPro extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor(): string
    {
        return 'auth_pro';
    }
}
