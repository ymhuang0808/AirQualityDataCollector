<?php

namespace App\Listeners;

use App\Events\CollectExceptionEvent;
use App\Notifications\CollectExceptionNotification;
use App\Recipients\AbstractRecipient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class CollectExceptionNotificationListener
{
    protected $recipient;

    /**
     * Create the event listener.
     *
     * @param AbstractRecipient $recipient
     */
    public function __construct(AbstractRecipient $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Handle the event.
     *
     * @param  CollectExceptionEvent  $event
     * @return void
     */
    public function handle(CollectExceptionEvent $event)
    {
        $hash = sha1($event->__toString());
        $key = 'notification-handle:' . $hash;

        if (Cache::has($key)) {
            return;
        }

        // TODO: Check the frequency to avoid sending email too often
        $message = $event->message;
        $happeningOn = $event->happeningOn;
        $context = $event->context;

        $notification = new CollectExceptionNotification($message, $happeningOn, $context);
        Notification::send($this->recipient, $notification);

        $expired = config('aqdc.notification.exception_frequency', 1800);
        Cache::put($key, 1, $expired);
    }
}
