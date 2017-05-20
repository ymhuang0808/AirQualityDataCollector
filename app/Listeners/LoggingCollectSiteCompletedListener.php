<?php

namespace App\Listeners;

use App\Events\CollectSiteCompletedEvent;
use App\Log\LogChannel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class LoggingCollectSiteCompletedListener
{
    protected $log;

    /**
     * Create the event listener.
     *
     * @param AbstractProcessingHandler $processingHandler
     */
    public function __construct(AbstractProcessingHandler $processingHandler)
    {
        $this->log = new Logger(LogChannel::COLLECT_SITE_CHANNEL);
        $this->log->pushHandler($processingHandler);
    }

    /**
     * Handle the event.
     *
     * @param CollectSiteCompletedEvent $event
     * @return void
     */
    public function handle(CollectSiteCompletedEvent $event)
    {
        $count = $event->sites->count();
        $sourceType = $event->type;

        $format = 'Collected %d sites from %s';
        $message = sprintf($format, $count, $sourceType);

        $this->log->info($message, [
            'count' => $count,
            'source_type' => $sourceType,
        ]);
    }
}
