<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Enums\AuthIdentifierType;

interface AuthIdentifierInterface
{
    public function getIdentifierType(): AuthIdentifierType;

    public function getIdentifierValue(): string;
}
