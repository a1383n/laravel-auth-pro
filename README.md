# Laravel Auth Pro

<p align="center">
  <a href="https://github.com/a1383n/laravel-auth-pro/actions"><img src="https://github.com/a1383n/laravel-auth-pro/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/a1383n/laravel-auth-pro"><img src="https://img.shields.io/packagist/dt/a1383n/laravel-auth-pro.svg?style=flat-square" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/a1383n/laravel-auth-pro"><img src="https://img.shields.io/packagist/v/a1383n/laravel-auth-pro.svg?style=flat-square" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/a1383n/laravel-auth-pro"><img src="https://img.shields.io/packagist/l/a1383n/laravel-auth-pro.svg?style=flat-square" alt="License"></a>
</p>

Laravel Auth Pro is a powerful and flexible authentication package for Laravel applications. It simplifies the implementation of advanced authentication systems, supporting multiple authentication providers, including email, phone, and OAuth.

## Features

- **Multi-Provider Authentication:** Easily configure and use various authentication methods, including email, phone, and popular OAuth providers.
- **Customizable:** Customize and extend authentication methods to suit your application's unique requirements.
- **One-Time Passwords (OTP):** Built-in support for OTP authentication via SMS or email.
- **OAuth Integration:** Seamlessly integrate with OAuth providers like Google, GitHub, etc., using Laravel Socialite.
- **User Management:** Manage user registration, password reset, and other common user-related tasks effortlessly.
- **Secure:** Implement robust security measures to protect user data and authentication processes.

## Installation

You can install Laravel Auth Pro via Composer:

```bash
composer require a1383n/laravel-auth-pro
```

After installing the package, you need to publish the configuration and migration files:

```bash
php artisan vendor:publish --provider="LaravelAuthPro\AuthProServiceProvider" --tag="config"
php artisan vendor:publish --provider="LaravelAuthPro\AuthProServiceProvider" --tag="migrations"
```

Then, run the migrations to create the `user_auth_providers` table:

```bash
php artisan migrate
```

## Configuration

The configuration file for Laravel Auth Pro is located at `config/auth_pro.php`. In this file, you can configure the authentication providers, sign-in methods, and other settings.

### Providers

The `providers` array allows you to enable and configure different authentication providers. By default, the `EmailProvider` is enabled. You can enable other providers like `PhoneProvider` and `OAuthProvider` as needed.

```php
'providers' => [
    \LaravelAuthPro\Contracts\Providers\EmailProviderInterface::class => [
        'enabled'    => true,
        'class'      => \LaravelAuthPro\Providers\EmailProvider::class,
        'credential' => \LaravelAuthPro\Credentials\EmailCredential::class,
    ],
    \LaravelAuthPro\Contracts\Providers\PhoneProviderInterface::class => [
        'enabled'    => true, // Set to true to enable phone provider
        'class'      => \LaravelAuthPro\Providers\PhoneProvider::class,
        'credential' => \LaravelAuthPro\Credentials\PhoneCredential::class,
    ],
    \LaravelAuthPro\Contracts\Providers\OAuthProviderInterface::class => [
        'enabled'    => true, // Set to true to enable OAuth provider
        'class'      => \LaravelAuthPro\Providers\OAuthProvider::class,
        'credential' => \LaravelAuthPro\Credentials\OAuthCredential::class,
        'drivers'    => ['google', 'github'], // Add the OAuth drivers you want to support
    ],
],
```

### User Model

You need to add the `AuthProAuthenticatable` trait to your `User` model.

```php
use LaravelAuthPro\Traits\AuthProAuthenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use AuthProAuthenticatable;

    // ...
}
```

## Usage

The main entry point for using this package is the `AuthPro` facade.

### Email/Password Authentication

```php
use Illuminate\Support\Facades\Auth;
use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Credentials\EmailCredential;

$credential = new EmailCredential('test@example.com', 'password');
$result = AuthPro::getService()->loginWithCredential($credential);

if ($result->isSuccessful()) {
    Auth::login($result->getAuthenticatable());
    // Redirect to dashboard
} else {
    // Handle login failure
}
```

### One-Time Password (OTP) Authentication

#### 1. Request an OTP

```php
use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Credentials\PhoneCredential;

$credential = new PhoneCredential('+1234567890');
$result = AuthPro::getService()->sendOneTimePassword($credential->getIdentifier());

if ($result->isSuccessful()) {
    // OTP sent successfully
} else {
    // Handle OTP sending failure
}
```

#### 2. Verify the OTP

```php
use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Credentials\PhoneCredential;

$credential = new PhoneCredential('+1234567890', '123456'); // '123456' is the OTP
$result = AuthPro::getService()->loginWithCredential($credential);

if ($result->isSuccessful()) {
    Auth::login($result->getAuthenticatable());
    // Redirect to dashboard
} else {
    // Handle OTP verification failure
}
```

### OAuth Authentication

To use OAuth authentication, you need to configure `laravel/socialite`. Please refer to the [Laravel Socialite documentation](https://laravel.com/docs/socialite) for more details.

#### 1. Redirect to Provider

```php
use Laravel\Socialite\Facades\Socialite;

return Socialite::driver('google')->redirect();
```

#### 2. Handle Provider Callback

```php
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Credentials\OAuthCredential;

$socialiteUser = Socialite::driver('google')->user();

$credential = new OAuthCredential('google', $socialiteUser);
$result = AuthPro::getService()->loginWithCredential($credential);

if ($result->isSuccessful()) {
    Auth::login($result->getAuthenticatable());
    // Redirect to dashboard
} else {
    // Handle login failure
}
```

## API Reference

### `LaravelAuthPro\Facades\AuthPro`

The `AuthPro` facade is the main entry point for accessing the package's services.

- `getService(): AuthServiceInterface`: Returns an instance of the `AuthServiceInterface`.

### `LaravelAuthPro\Contracts\AuthServiceInterface`

This interface defines the main authentication methods.

- `loginWithCredential(AuthCredentialInterface $credential): AuthResultInterface`: Authenticates a user with the given credential.
- `sendOneTimePassword(AuthIdentifierInterface $identifier): AuthResultInterface`: Sends an OTP to the given identifier (e.g., phone number or email).
- `verifyOneTimePassword(PhoneCredentialInterface $phoneCredential, bool $dry = false): AuthResultInterface`: Verifies an OTP.
- `getOneTimePasswordSignature(PhoneCredentialInterface $phoneCredential, string $ip): AuthResultInterface`: get one time password signature.
- `verifyOneTimePasswordSignature(AuthSignatureInterface $signature): AuthResultInterface`: verify one time password signature.

### Credentials

- `LaravelAuthPro\Credentials\EmailCredential(string $email, ?string $password = null)`
- `LaravelAuthPro\Credentials\PhoneCredential(string $phoneNumber, ?string $otp = null)`
- `LaravelAuthPro\Credentials\OAuthCredential(string $provider, \Laravel\Socialite\Contracts\User $socialiteUser)`

## Contributing

We welcome contributions! Please read our [contribution guidelines](.github/CONTRIBUTING.md) before submitting any pull requests.

## License

Laravel Auth Pro is open-source software licensed under the [MIT License](LICENSE).
