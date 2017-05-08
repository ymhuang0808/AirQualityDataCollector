<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CollectSiteCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sites;

    public $type;

    /**
     * Create a new event instance.
     *
     * @param Collection $sites
     * @param string $type
     */
    public function __construct(Collection $sites, string $type)
    {
        $this->sites = $sites;
        $this->type = $type;
    }
}