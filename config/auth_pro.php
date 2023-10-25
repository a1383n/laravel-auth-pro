<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Providers
    |--------------------------------------------------------------------------
    |
    | Define the authentication providers for your package here.
    | You can specify custom provider implementations.
    |
    */

    'providers' => [
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Providers\EmailProvider::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Providers\PhoneProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Credential Implementations
    |--------------------------------------------------------------------------
    |
    | Define the credential implementations for your authentication providers.
    | Customize the credential classes for different providers as needed.
    |
    */

    'credentials' => [
        \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => \LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface::class,
        \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => \LaravelAuthPro\Credentials\PhoneCredential::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Customize multi-provider authentication options here.
    |
    */

    'multi_provider' => [

        /*
        |--------------------------------------------------------------------------
        | Default Multi-Provider
        |--------------------------------------------------------------------------
        |
        | Specify the default multi-provider to use when multiple providers
        | are available. Users can choose their preferred provider during
        | authentication.
        |
        */

        'default' => 'email',

    ],

    /*
    |--------------------------------------------------------------------------
    | One-Time Password (OTP) Settings
    |--------------------------------------------------------------------------
    |
    | Configure one-time password (OTP) generation and notification settings.
    |
    */

    'one_time_password' => [

        /*
        |--------------------------------------------------------------------------
        | OTP Driver
        |--------------------------------------------------------------------------
        |
        | Specify the OTP driver to use for generating and verifying OTP codes.
        | Supported drivers: 'cache', 'database', 'redis', and more.
        |
        | Description: The OTP driver determines how OTP codes are generated and
        | verified. Choose the appropriate driver for your application's needs.
        |
        */

        'driver' => 'cache', // 'cache', 'database', 'redis', etc.

        /*
        |--------------------------------------------------------------------------
        | OTP Driver Configurations
        |--------------------------------------------------------------------------
        |
        | Define the configurations for each OTP driver.
        |
        */

        'drivers' => [

            'cache' => [

                /*
                |--------------------------------------------------------------------------
                | Cache Store
                |--------------------------------------------------------------------------
                |
                | Specify the cache store to use for OTP code storage.
                |
                | Description: Choose the cache store where OTP codes will be stored.
                | Common options include 'file', 'redis', 'memcache', etc.
                |
                */

                'store' => 'redis',

                /*
                |--------------------------------------------------------------------------
                | Redis Store
                |--------------------------------------------------------------------------
                |
                | Specify the redis store configuration
                */

                'redis' => [
                    'prefix' => 'auth_pro_otp',
                    'database' => 3
                ]
            ],

            'database' => [

                /*
                |--------------------------------------------------------------------------
                | Database Table
                |--------------------------------------------------------------------------
                |
                | Define the database table for storing OTP codes.
                |
                | Description: Set the name of the database table where OTP codes will
                | be stored and retrieved.
                |
                */

//                'model' => LaravelAuthPro\Infrastructure\OneTimePassword\Models\OneTimePasswordModel::class,
            ],
        ],

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
                'email' => ['mail'],
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
            'expiry' => 60
        ],

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
            'length' => 16,
            'type' => \LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordTokenType::RANDOM_STRING,
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
            'type' => \LaravelAuthPro\Infrastructure\OneTimePassword\Enum\OneTimePasswordCodeType::DIGIT,
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
            \LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\OneTimePasswordIpAddressLimiter::class => [
                'decay_in_seconds' => 1800,
                'max_attempts' => 5
            ],
            \LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\OneTimePasswordIdentifierLimiter::class => [
                'decay_in_seconds' => 900,
                'max_attempts' => 10
            ]
        ]
    ],
];
