<?php

namespace LaravelAuthPro\Notifications\Contracts;

use Illuminate\Notifications\Messages\MailMessage;

interface MailNotificationInterface
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage;
}
