<?php

namespace App\Listeners;

use App\Events\CollectAirQualityCompletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CollectAirQualityCompletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CollectAirQualityCompletedEvent  $event
     * @return void
     */
    public function handle(CollectAirQualityCompletedEvent $event)
    {
        // Insert a new record in Log model with time and the amount of air quality items
        $format = 'Collected %d air quality dataset items';
        $message = sprintf($format, $event->dataset->count(), $event->type);
    }
}
