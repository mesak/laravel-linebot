<?php

namespace Mesak\LineBot\Actions;

use LINE\LINEBot\MessageBuilder;

class MessageAction extends BaseAction
{

  public function __construct(MessageBuilder $message)
  {

    $this->message = $message;

  }
  
}
