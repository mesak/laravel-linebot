<?php

namespace Mesak\LineBot\Facades;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Facade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mesak\LineBot\Events\MessageEvent;

class Listener extends Facade implements ShouldQueue
{
    protected static $backTypeToAction = [
        'string' => 'Mesak\LineBot\Actions\TextAction',
        'array' => 'Mesak\LineBot\Actions\RawAction',
    ];
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return config('linebot.listener', \Mesak\LineBot\Listener\LineBotMessage::class);
    }
    public function __invoke(MessageEvent $event)
    {
        $lineEvent = $event->getEvent();

        $method = 'on' . Str::studly($lineEvent->getType());

        $listeners = $this->getFacadeRoot();

        $actionResponse = (method_exists($listeners, $method)) ? [$listeners, $method]($lineEvent) : null;

        $backActionType = gettype($actionResponse);

        if (!is_null($actionResponse) && $backActionClass = self::$backTypeToAction[$backActionType]) {

            tap(new $backActionClass($actionResponse), function ($action) use ($lineEvent) {
                
                $action->setEvent($lineEvent);
                
                event($action);
                
            });
        }
    }
}
