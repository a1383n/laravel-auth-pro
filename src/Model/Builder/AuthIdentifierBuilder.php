<?php

namespace LaravelAuthPro\Model\Builder;

use Illuminate\Validation\ValidationException;
use LaravelAuthPro\AuthIdentifier;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\EntityBuilderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

/**
 * @implements EntityBuilderInterface<AuthIdentifierInterface>
 */
class AuthIdentifierBuilder implements EntityBuilderInterface
{
    private ?AuthIdentifierType $identifierType = null;
    private ?string $value = null;

    /**
     * @param string $identifier
     * @return self
     * @throws ValidationException if unprocessable identifier passed
     */
    public function fromPlainIdentifier(string $identifier): self
    {
        $isPhone = ctype_digit(str_replace('+', '', $identifier));
        if ($isPhone) {
            throw new \Exception('not implemented');

            /**
             * @phpstan-ignore-next-line
             */
            $this->identifierType = AuthIdentifierType::MOBILE;
            $this->value = str_replace(' ', '', $identifier);
        } else {
            $this->identifierType = AuthIdentifierType::EMAIL;
            $this->value = $identifier;
        }

        return $this;
    }

    public function build(): AuthIdentifierInterface
    {
        if ($this->identifierType === null || $this->value === null) {
            throw new \InvalidArgumentException('$identifier or $value is null');
        }

        return new AuthIdentifier($this->identifierType, $this->value);
    }
}
