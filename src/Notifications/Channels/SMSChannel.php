<?php

namespace LaravelAuthPro\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use LaravelAuthPro\Notifications\Contracts\NotificationChannelInterface;
use LaravelAuthPro\Notifications\Contracts\SMSNotificationInterface;
use LaravelAuthPro\Notifications\Messages\SMSMessage;

class SMSChannel implements NotificationChannelInterface
{
    public function send(?object $notifiable, Notification $notification): mixed
    {
        if (! $notification instanceof SMSNotificationInterface) {
            throw new \InvalidArgumentException('$notification is not instance of SMSNotification');
        }

        /**
         * @var SMSMessage $message
         */
        $message = $notification->toSMS($notifiable);

        if (app()->isLocal()) {
            Log::debug($message->content, $message->toArray());
        } else {
            //TODO: Implement this
        }

        return $message;
    }
}
