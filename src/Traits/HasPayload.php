<?php

namespace LaravelAuthPro\Traits;

trait HasPayload
{
    /**
     * @param array<string, string> $payload
     * @return void
     */
    private function fillAttributes(array $payload): void
    {
        collect(get_class_vars(static::class))
            ->keys()
            ->diff(collect(get_object_vars($this))->keys())
            ->each(function ($property) use ($payload) {
                if (! empty($payload[$property])) {
                    $this->{$property} = $payload[$property];
                }
            });
    }
}
