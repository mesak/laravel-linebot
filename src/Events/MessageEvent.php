<?php

namespace Mesak\LineBot\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent
{
    use Dispatchable, SerializesModels;

    protected $event;

    protected $triggerName;

    public function __construct($event) 
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }
}
