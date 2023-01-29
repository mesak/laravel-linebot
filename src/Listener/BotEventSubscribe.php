<?php

namespace Mesak\LineBot\Listener;

use Mesak\LineBot\Contracts\Bot as BotContract;
use Mesak\LineBot\Actions\BaseAction;
use Mesak\LineBot\Actions\TextAction;
use Mesak\LineBot\Actions\RawAction;

class BotEventSubscribe
{
    protected $bot;

    public function __construct(BotContract $bot)
    {

        $this->bot = $bot;
    }

    public function handle(BaseAction $action): void
    {
        $type = $action->getType();

        if (is_null($type)) {

            throw new \Exception('LINE Bot send type is not set');

        }

        $target = $action->getTarget();

        if (is_null($target)) {

            throw new \Exception('LINE Bot send target is not set');
        }

        $this->bot->execute($type, $target, $action->getMessage());
    }

    public function subscribe(): array
    {
        return [
            TextAction::class => 'handle',
            RawAction::class => 'handle',
        ];
    }
}
