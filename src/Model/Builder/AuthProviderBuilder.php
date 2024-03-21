<?php

namespace LaravelAuthPro\Model\Builder;

use Illuminate\Container\Container;
use LaravelAuthPro\AuthProServiceProvider;
use LaravelAuthPro\Contracts\AuthProviderInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;

class AuthProviderBuilder implements EntityBuilderInterface
{
    protected ?string $providerId;
    protected ?string $providerClass;

    public function setProviderId(?string $providerId = null): AuthProviderBuilder
    {
        $this->providerId = $providerId;

        return $this;
    }

    public function setProviderClass(?string $providerClass = null): AuthProviderBuilder
    {
        $this->providerClass = $providerClass;

        return $this;
    }

    public function fromId(string $providerId): AuthProviderInterface
    {
        $this->providerId = $providerId;

        return $this->build();
    }

    public function fromClass(string $providerClass): AuthProviderInterface
    {
        $this->providerClass = $providerClass;

        return Container::getInstance()
            ->make($providerClass);
    }

    public function build(): AuthProviderInterface
    {
        if (empty($this->providerId) ^ empty($this->providerClass)) {
            return $this->fromClass($this->providerClass ?? sprintf(AuthProServiceProvider::CONTAINER_ALIAS_AUTH_PROVIDER_TEMPLATE, $this->providerId));
        } else {
            throw new \InvalidArgumentException('Only one of $providerId or $providerClass should be set');
        }
    }
}
