<?php

namespace LaravelAuthPro\Model\Builder;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;

class AuthenticatableBuilder implements EntityBuilderInterface
{
    protected AuthIdentifierInterface $authIdentifier;
    protected ?string $password;

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

    public function build(): AuthenticatableInterface
    {
        $identifierKey = collect($this->authenticatableModel::getIdentifierMapper())
            ->filter(fn($value) => $value == $this->authIdentifier->getIdentifierType())
            ->keys()
            ->first();

        $attributes = [
            $identifierKey => $this->authIdentifier->getIdentifierValue(),
        ];

        if (!empty($this->password)) {
            $attributes[$this->authenticatableModel::getPasswordKey()] = $this->password;
        }

        return new $this->authenticatableModel($attributes);
    }
}
