<?php

namespace Mesak\LineBot\Actions;

use LINE\LINEBot\MessageBuilder\RawMessageBuilder;

class RawAction extends BaseAction
{
  
  public function __construct($rawData)
  {

    $this->message = new RawMessageBuilder($rawData);

  }

}
