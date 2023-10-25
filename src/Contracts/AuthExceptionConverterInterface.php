<?php

namespace LaravelAuthPro\Contracts;

interface AuthExceptionConverterInterface
{
    public function convert(AuthExceptionInterface $exception): AuthExceptionInterface;
}
