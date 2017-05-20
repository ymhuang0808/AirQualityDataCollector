<?php

namespace App\Listeners;

use App\Events\CollectAirQualityCompletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class CollectAirQualityCompletedListener
{
    protected $log;

    /**
     * Create the event listener.
     * @param AbstractProcessingHandler $processingHandler
     */
    public function __construct(AbstractProcessingHandler $processingHandler)
    {
        $this->log = new Logger('collect-air');
        $this->log->pushHandler($processingHandler);
    }

    /**
     * Handle the event.
     *
     * @param  CollectAirQualityCompletedEvent  $event
     * @return void
     */
    public function handle(CollectAirQualityCompletedEvent $event)
    {
        $count = $event->dataset->count();

        // Insert a new record with time and the amount of air quality items
        $format = 'Collected %d air quality dataset items from %s';
        $message = sprintf($format, $count, $event->type);

        $this->log->info($message, [
            'count' => $count,
            'source_type' => $event->type,
        ]);
    }
}
