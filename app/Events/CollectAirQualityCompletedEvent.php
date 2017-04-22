<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

class CollectAirQualityCompletedEvent extends Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dataset;

    public $type;

    /**
     * Create a new event instance.
     *
     * @param Collection $dataset
     * @param string $type
     * @internal param Collection $datasetCollection
     */
    public function __construct(Collection $dataset, string $type)
    {
        $this->dataset = $dataset;
        $this->type = $type;
    }
}
