<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Pushbullet\PushbulletChannel;
use NotificationChannels\Pushbullet\PushbulletMessage;

class CollectExceptionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    use PushbulletTrait;

    public $message;

    public $happeningOn;

    /**
     * @var
     */
    public $context;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     * @param string|array $happeningOn
     * @param array $context
     */
    public function __construct(string $message, $happeningOn = '', array $context = [])
    {
        $this->message = $message;
        $this->happeningOn = $happeningOn;
        $this->context = $context;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $result = ['mail'];

        if ($this->isPushbulletAvailable()) {
            $result[] = PushbulletChannel::class;
        }

        return $result;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();
        $mailMessage
            ->error()
            ->subject('Collect exception occurs')
            ->line('Air quality data collector encounters an exception, please see the following information:')
            ->line($this->message);

        if (is_array($this->happeningOn)) {
            $attached = implode(PHP_EOL, $this->happeningOn);
            $mailMessage->attachData($attached, 'detail.txt', [
                'mime' => 'text/plain',
            ]);
        } else if (is_string($this->happeningOn)) {
            $mailMessage
                ->line('Happening on:')
                ->line($this->happeningOn);
        }

        return $mailMessage;
    }

    public function toPushbullet($notifiable)
    {
        $message = 'Air quality data collector encounters an exception, please see the following information:' . PHP_EOL .
            $this->message . PHP_EOL .
            'Happening on:' . PHP_EOL .
            $this->happeningOn;

        return PushbulletMessage::create()
            ->note()
            ->title('Collect exception occurs')
            ->message($message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
