<?php

namespace LaravelAuthPro\Credentials\Builder;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use LaravelAuthPro\AuthPro;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;

/**
 * @implements EntityBuilderInterface<AuthCredentialInterface>
 */
class AuthCredentialBuilder implements EntityBuilderInterface
{
    private ?string $providerId = null;
    private ?AuthIdentifierInterface $identifier = null;
    private ?AuthProviderSignInMethod $signInMethod = null;

    /**
     * @var array<string, string>
     */
    private ?array $payload = null;

    /**
     * @return class-string<AuthCredentialInterface>
     */
    public static function getClassFromProviderId(string $id): string
    {
        /**
         * @phpstan-ignore-next-line
         */
        return Collection::make(AuthPro::getCredentialsMapper())
            ->first(fn ($item, $key) => AuthPro::getAuthProvidersMapper()[$key]::ID == $id);
    }

    public function with(string $providerId): self
    {
        $this->providerId = $providerId;

        return $this;
    }

    public function as(AuthIdentifierInterface $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function by(AuthProviderSignInMethod $signInMethod): self
    {
        $this->signInMethod = $signInMethod;

        return $this;
    }

    /**
     * @param array<string, string> $payload
     * @return $this
     */
    public function withPayload(array $payload = []): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function build(): AuthCredentialInterface
    {
        if (empty($this->providerId)) {
            throw new \InvalidArgumentException('$providerId is null');
        }

        return Container::getInstance()
            ->make(self::getClassFromProviderId($this->providerId), [
                'providerId' => $this->providerId,
                'identifier' => $this->identifier,
                'signInMethod' => $this->signInMethod,
                'payload' => $this->payload,
            ]);
    }
}
