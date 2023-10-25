<?php

namespace LaravelAuthPro\Notifications\Contracts;

interface SMSNotificationInterface
{
    public function toSMS(?object $notifiable): NotificationMessageInterface;
}
