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
        $staticProperties = array_keys(get_class_vars(static::class));
        $staticProperties = array_diff($staticProperties, array_keys(get_object_vars($this)));

        foreach ($staticProperties as $property) {
            if (!empty($payload[$property])) {
                $this->{$property} = $payload[$property];
            }
        }
    }
}
