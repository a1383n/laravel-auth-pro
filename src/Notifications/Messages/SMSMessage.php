<?php

namespace LaravelAuthPro\Notifications\Messages;

use LaravelAuthPro\Notifications\Contracts\NotificationMessageInterface;

class SMSMessage implements NotificationMessageInterface
{
    public function __construct(public string $to,public string $content)
    {
        //
    }

    public function toArray(): array
    {
        return [
            'to' => $this->to,
            'content' => $this->content
        ];
    }
}
