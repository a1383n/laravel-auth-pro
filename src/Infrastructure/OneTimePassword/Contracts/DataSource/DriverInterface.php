<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource;

interface DriverInterface
{
    public function delete(...$arguments): int;
}
