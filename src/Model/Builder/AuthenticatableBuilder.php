<?php

namespace LaravelAuthPro\Model\Builder;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;

class AuthenticatableBuilder implements EntityBuilderInterface
{
    protected AuthIdentifierInterface $authIdentifier;
    protected ?string $password;
    protected array $attributes = [];

    public function __construct(protected readonly string $authenticatableModel)
    {
        //
    }

    public function as(AuthIdentifierInterface $identifier): self
    {
        $this->authIdentifier = $identifier;

        return $this;
    }

    public function withPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function withAttributes(array $attributes = []): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function build(): AuthenticatableInterface
    {
        $identifierKey = collect($this->authenticatableModel::getIdentifierMapper())
            ->filter(fn ($value) => $value == $this->authIdentifier->getIdentifierType())
            ->keys()
            ->first();

        $this->attributes = [$identifierKey => $this->authIdentifier->getIdentifierValue()] + $this->attributes;

        if (! empty($this->password)) {
            $this->attributes[$this->authenticatableModel::getPasswordKey()] = $this->password;
        }

        return new $this->authenticatableModel($this->attributes);
    }
}
