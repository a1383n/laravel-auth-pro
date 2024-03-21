<?php

namespace LaravelAuthPro;

use Illuminate\Notifications\RoutesNotifications;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Model\Builder\AuthIdentifierBuilder;
use LaravelAuthPro\Traits\HasBuilder;

class AuthIdentifier implements AuthIdentifierInterface
{
    use HasBuilder, RoutesNotifications;

    protected AuthIdentifierType $type;
    protected string $value;

    /**
     * @param AuthIdentifierType $type
     * @param string $value
     */
    public function __construct(AuthIdentifierType $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    protected static function getBuilderClass(): string
    {
        return AuthIdentifierBuilder::class;
    }

    public function getIdentifierType(): AuthIdentifierType
    {
        return $this->type;
    }

    public function getIdentifierValue(): string
    {
        return $this->value;
    }

    public function routeNotificationForDatabase(): ?string
    {
        return null;
    }

    public function routeNotificationForMail(): ?string
    {
        return $this->type == AuthIdentifierType::EMAIL ? $this->value : null;
    }

    public function routeNotificationForSMS(): ?string
    {
        return $this->type == AuthIdentifierType::MOBILE ? $this->value : null;
    }
}
