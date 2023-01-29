<?php

namespace Mesak\LineBot\Contracts;

use LINE\LINEBot\MessageBuilder;
use Illuminate\Http\Request;

/**
 * @see \App\Contracts\LineBot
 */

interface Bot
{

  /**
   * @param Request $request
   * @return void
   */
  public function handle(Request $request): void;

  /**
   * @param String $type
   * @param String $target
   * @param MessageBuilder $message
   * @return void
   */
  public function execute(String $type, String $target, MessageBuilder $message): void;
}
