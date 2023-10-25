<?php

namespace LaravelAuthPro\Contracts\Credentials\Base;

interface HasPasswordInterface
{
    public function getPassword(): ?string;

    /**
     * @return array<string, string|string[]>
     */
    public static function getPasswordRule(): array;
}
