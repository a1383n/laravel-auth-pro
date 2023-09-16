<?php

namespace LaravelAuthPro;

use Illuminate\Notifications\RoutesNotifications;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\HasBuilderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Model\Builder\AuthIdentifierBuilder;

/**
 * @implements HasBuilderInterface<AuthIdentifierInterface>
 */
class AuthIdentifier implements AuthIdentifierInterface, HasBuilderInterface
{
    use RoutesNotifications;

    protected const PAYLOAD_NAME_IDENTIFIER_TYPE_MAPPER = [
        'email' => AuthIdentifierType::EMAIL,
        'phone' => AuthIdentifierType::MOBILE,
    ];

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

    public static function getBuilder(): AuthIdentifierBuilder
    {
        return new AuthIdentifierBuilder();
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
