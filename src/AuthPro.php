<?php

namespace LaravelAuthPro;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAuthProvidersConfiguration()
 * @method static array getCredentialsMapper()
 * @method static string getDefaultAuthenticatableModel()
 * @method static array getDefaultSignInMethodsMapper()
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
