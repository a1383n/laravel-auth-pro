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
            Log::debug($message->to, $message->toArray());
        } else {
            $this->sendSMS($message);
        }

        return $message;
    }

    protected function sendSMS(SMSMessage $message)
    {
        //TODO: Not implemented
        throw new \RuntimeException('Not implemented');
    }
}
