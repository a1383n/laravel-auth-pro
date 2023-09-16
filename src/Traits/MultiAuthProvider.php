<?php

namespace LaravelAuthPro\Traits;

use App\Models\UserProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LaravelAuthPro\Contracts\UserProviderInterface;

/**
 * @mixin Model
 */
trait MultiAuthProvider
{
    /**
     * @return HasMany<UserProvider>
     */
    public function userProviders(): HasMany
    {
        return $this->hasMany(UserProvider::class, 'user_id');
    }

    /**
     * @phpstan-ignore-next-line
     * @return Collection<UserProviderInterface>
     */
    public function getProviders(): Collection
    {
        return $this->userProviders->values();
    }

    public function hasProvider(string $providerId): bool
    {
        if ($this->relationLoaded('userProviders')) {
            return $this->getProviders()->where('provider_id', '=', $providerId)->isNotEmpty();
        } else {
            return $this->userProviders()->where('provider_id', '=', $providerId)->exists();
        }
    }
}
