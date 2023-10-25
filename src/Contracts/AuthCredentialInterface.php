<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;

interface AuthCredentialInterface
{
    /**
     * @param string $providerId
     * @param AuthIdentifierInterface $identifier
     * @param AuthProviderSignInMethod $signInMethod
     * @param array<string, string> $payload
     */
    public function __construct(string $providerId, AuthIdentifierInterface $identifier, AuthProviderSignInMethod $signInMethod, array $payload);

    /**
     * @return array<string, string|string[]>
     */
    public static function getPayloadRules(): array;

    public function getProviderId(): string;

    public function getIdentifier(): AuthIdentifierInterface;

    public function getSignInMethod(): AuthProviderSignInMethod;

    /**
     * @return AuthIdentifierType[]
     */
    public function getSupportedIdentifiersTypes(): array;
}
