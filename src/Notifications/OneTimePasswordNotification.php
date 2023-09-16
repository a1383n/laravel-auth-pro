<?php

namespace LaravelAuthPro\Notifications;

use LaravelAuthPro\Notifications\Channels\SMSChannel;
use LaravelAuthPro\Notifications\Contracts\MailNotificationInterface;
use LaravelAuthPro\Notifications\Contracts\NotificationMessageInterface;
use LaravelAuthPro\Notifications\Contracts\SMSNotificationInterface;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Infrastructure\OneTimePassword\Repositories\Contracts\OneTimePasswordRepositoryInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;
use LaravelAuthPro\Notifications\Messages\SMSMessage;

class OneTimePasswordNotification extends Notification implements ShouldQueue, ShouldBeEncrypted, SMSNotificationInterface, MailNotificationInterface
{
    use Queueable;

    protected string $tag;

    protected string $code;

    protected string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(OneTimePasswordEntityInterface $entity)
    {
        $this->tag = $entity->getIdentifier()->getIdentifierType()->value. ':' . $entity->getIdentifier()->getIdentifierValue();
        $this->code = $entity->getCode();
        $this->token = $entity->getToken();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string|class-string>
     */
    public function via(AuthIdentifierInterface $notifiable): array
    {
        return match ($notifiable->getIdentifierType()) {
            AuthIdentifierType::EMAIL => ['mail'],
            AuthIdentifierType::MOBILE => [SMSChannel::class],
        };
    }

    /**
     * @param object|null $notifiable
     * @param string $channel
     * @return array<string|class-string, CarbonInterface>|CarbonInterface|null
     */
    public function withDelay(?object $notifiable, string $channel): array|CarbonInterface|null
    {
        return match ($channel) {
            SMSChannel::class => now()->addSeconds(3),
            default => null
        };
    }

    public function shouldSend(AuthIdentifierInterface $notifiable, string $channel): bool
    {
        return app(OneTimePasswordRepositoryInterface::class)
            ->isOneTimePasswordExists($notifiable, $this->token);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('OTP Code')
            ->line('You are receiving this email for OTP verification.')
            ->line('Your OTP code is: ' . $this->code)
            ->line('If you did not request this OTP, no further action is required.');
    }

    public function toSMS(?object $notifiable): NotificationMessageInterface
    {
        if (!$notifiable instanceof AuthIdentifierInterface)
            throw new \InvalidArgumentException('$notifiable is not supported');

        return new SMSMessage(
            $notifiable->getIdentifierValue(),
            __('auth.otp.sms.template', ['app_name' => __('global.app_name'), 'code' => $this->code])
        );
    }

    /**
     * @return string[]
     */
    public function tags(): array
    {
        //TODO: We can use first 8 character of hash of the tag for more security
        return [$this->tag];
    }
}
