<?php

namespace Mesak\LineBot\Actions;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class TextAction extends BaseAction
{

  public function __construct($text)
  {

    $this->message = new TextMessageBuilder($text);

  }
  
}
