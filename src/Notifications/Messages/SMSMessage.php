<?php

namespace LaravelAuthPro\Notifications\Messages;

use LaravelAuthPro\Notifications\Contracts\NotificationMessageInterface;

class SMSMessage implements NotificationMessageInterface
{
    /**
     * @param string $to
     * @param array<string, mixed> $attributes
     */
    public function __construct(public string $to, public array $attributes)
    {
        //
    }

    public function toArray(): array
    {
        return [
            'to' => $this->to,
            'attributes' => $this->attributes,
        ];
    }
}
