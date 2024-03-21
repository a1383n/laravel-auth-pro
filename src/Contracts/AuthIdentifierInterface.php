<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Contracts\Base\HasBuilderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;

interface AuthIdentifierInterface extends HasBuilderInterface
{
    public function getIdentifierType(): AuthIdentifierType;

    public function getIdentifierValue(): string;
}
