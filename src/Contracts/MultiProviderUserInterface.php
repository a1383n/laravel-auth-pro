<?php

namespace LaravelAuthPro\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MultiProviderUserInterface
{
    public function hasProvider(string $providerId): bool;

    /**
     * @return Collection<UserProviderInterface>
     */
    public function getProviders(): Collection;
}
