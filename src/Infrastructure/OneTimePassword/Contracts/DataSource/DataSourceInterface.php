<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\DataSource;

interface DataSourceInterface
{
    public function __construct(DriverInterface $driver);

    public function getDriver(): DriverInterface;
}
