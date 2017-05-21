<?php

namespace App\Listeners;

use App\Events\CollectExceptionEvent;
use App\Notifications\CollectExceptionNotification;
use App\Recipients\AbstractRecipient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class CollectSiteCompletedListener
{
    protected $recipient;

    /**
     * Create the event listener.
     *
     * @return void
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
        $message = $event->message;
        $happeningOn = $event->happeningOn;
        $context = $event->context;

        $notification = new CollectExceptionNotification($message, $happeningOn, $context);
        Notification::send($this->recipient, $notification);
    }
}
