<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Event;

class CollectExceptionEvent extends Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $happeningOn;

    public $context;

    /**
     * Create a new event instance.
     *
     * @param string $message
     * @param $happeningOn
     * @param array $context
     */
    public function __construct(string $message, $happeningOn, array $context = [])
    {
        $this->message = $message;
        $this->happeningOn = $happeningOn;
        $this->context = $context;
    }

    public function __toString()
    {
        $string = $this->message;

        if (is_array($this->happeningOn)) {
            $string .= ' ' . implode(' ', $this->happeningOn);
        } else {
            $string .= $this->happeningOn;
        }

        $string .= ' ' . implode(' ', $this->context);

        return $string;
    }
}
