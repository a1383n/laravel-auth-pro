<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Providers
    |--------------------------------------------------------------------------
    |
    | Define the authentication providers for your package here.
    | You can specify custom provider implementations along with their credentials.
    |
    */

    'providers' => [
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => [
            'enabled'    => true,
            'class'      => \LaravelAuthPro\Providers\EmailProvider::class,
            'credential' => \LaravelAuthPro\Credentials\EmailCredential::class,
        ],
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => [
            'enabled'    => false,
            'class'      => \LaravelAuthPro\Providers\PhoneProvider::class,
            'credential' => \LaravelAuthPro\Credentials\PhoneCredential::class,
        ],
        \LaravelAuthPro\Contracts\Providers\OAuthProviderInterface::class => [
            'enabled'    => false,
            'class'      => \LaravelAuthPro\Providers\OAuthProvider::class,
            'credential' => \LaravelAuthPro\Credentials\OAuthCredential::class,
            'drivers'    => ['google', 'github'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sign in Methods
    |--------------------------------------------------------------------------
    |
    | Define the sign in methods for your authentication providers.
    | Customize the sign in method classes as needed.
    |
    */

    'sign_in_methods' => [
        'password' => \LaravelAuthPro\SignInMethods\PasswordSignInMethod::class,
        'otp'      => \LaravelAuthPro\SignInMethods\OneTimePasswordSignInMethod::class,
        'oauth'    => \LaravelAuthPro\SignInMethods\OAuthSignInMethod::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Authenticatable Model
    |--------------------------------------------------------------------------
    |
    | Specify the default authenticatable model used by the package.
    | This model will be used if not explicitly specified in the provider.
    |
    */

    'default_authenticatable_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | One-Time Password (OTP) Settings
    |--------------------------------------------------------------------------
    |
    | Configure one-time password (OTP) generation and notification settings.
    |
    */

    'one_time_password' => [

        'notification' => [

            /*
            |--------------------------------------------------------------------------
            | OTP Notification Class
            |--------------------------------------------------------------------------
            |
            | Specify the class responsible for sending OTP notifications.
            | Customize this based on your notification needs.
            |
            */

            'class' => \LaravelAuthPro\Notifications\OneTimePasswordNotification::class,

            /*
            |--------------------------------------------------------------------------
            | Notification Channels
            |--------------------------------------------------------------------------
            |
            | Define how OTPs should be delivered for different providers.
            |
            */

            'via' => [
                'email'  => ['mail'],
                'mobile' => [\LaravelAuthPro\Notifications\Channels\SMSChannel::class],
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | OTP Signature Configuration
        |--------------------------------------------------------------------------
        |
        | Configure the expiry and other setting for signatures
        |
        */
        'signature' => [
            'expiry' => 60,
        ],

        /*
        |--------------------------------------------------------------------------
        | Allow Simultaneous Multiple OTP Requests
        |--------------------------------------------------------------------------
        |
        | Set this to true to allow multiple OTP requests simultaneously. When set
        | to false, attempting to request an OTP while one is pending will return
        | 'auth.error.conflict'.
        |
        | Note: When setting this to false, you should set 'token.enabled' to false too
        */

        'allow_simultaneously_multiple_requests' => true,

        /*
        |--------------------------------------------------------------------------
        | OTP Token Configuration
        |--------------------------------------------------------------------------
        |
        | Configure the length and type of OTP tokens generated.
        |
        */

        'token' => [
            'enabled' => true,
            'length'  => 16,
            'type'    => \LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordTokenType::RANDOM_STRING,
        ],

        /*
        |--------------------------------------------------------------------------
        | OTP Code Configuration
        |--------------------------------------------------------------------------
        |
        | Configure the length and type of OTP codes generated.
        |
        */

        'code' => [
            'length' => 6,
            'type'   => \LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordCodeType::DIGIT,
        ],

        /*
        |--------------------------------------------------------------------------
        | OTP Code Expiry
        |--------------------------------------------------------------------------
        |
        | Set the expiration time (in seconds) for OTP codes.
        |
        */

        'expiry' => 120,

        /*
        |--------------------------------------------------------------------------
        | OTP Maximum Attempts
        |--------------------------------------------------------------------------
        |
        | Define the maximum number of OTP code verification attempts allowed.
        |
        */

        'max_attempts' => 3,

        /*
        |--------------------------------------------------------------------------
        | OTP Rate-limit configurations
        |--------------------------------------------------------------------------
        |
        | Define the rate-limit configuration
        |
        */
        'rate_limit' => [
            'name' => 'auth_pro_otp',
        ],
    ],
];
