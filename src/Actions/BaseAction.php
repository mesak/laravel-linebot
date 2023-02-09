<?php

namespace Mesak\LineBot\Actions;

use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\Event\BaseEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class BaseAction
{

  use Dispatchable, SerializesModels;

  protected $event;

  protected $type = 'push';

  protected $target;

  protected $message;

  public function setEvent(BaseEvent $event): void
  {
    $this->event = $event;

    $this->type = 'reply';

    $this->target = $event->getReplyToken();

  }

  public function getType(): string
  {
    return $this->type;
  }

  public function getTarget(): string
  {
    return $this->target;
  }
  
  public function getMessage(): MessageBuilder
  {
    
    if( is_string($this->message) )
    {
      return new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($this->message);
    }
      
    return $this->message;
  }
}