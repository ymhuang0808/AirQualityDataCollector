<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/4/15
 * Time: 下午9:22
 */

namespace App\Events;


class CollectSiteCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sourceType)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}