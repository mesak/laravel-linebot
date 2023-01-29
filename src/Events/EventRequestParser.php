<?php

namespace Mesak\LineBot\Events;

use LINE\LINEBot\Event\UnknownEvent;
use LINE\LINEBot\Event\MessageEvent\UnknownMessage;

class EventRequestParser
{
  public static $eventMap = [
    'message'              => 'LINE\LINEBot\Event\MessageEvent',
    'unsend'               => 'LINE\LINEBot\Event\UnsendEvent',
    'follow'               => 'LINE\LINEBot\Event\FollowEvent',
    'unfollow'             => 'LINE\LINEBot\Event\UnfollowEvent',
    'join'                 => 'LINE\LINEBot\Event\JoinEvent',
    'leave'                => 'LINE\LINEBot\Event\LeaveEvent',
    'postback'             => 'LINE\LINEBot\Event\PostbackEvent',
    'videoPlayComplete'    => 'LINE\LINEBot\Event\VideoPlayCompleteEvent',
    'beacon'               => 'LINE\LINEBot\Event\BeaconDetectionEvent',
    'accountLink'          => 'LINE\LINEBot\Event\AccountLinkEvent',
    'memberJoined'         => 'LINE\LINEBot\Event\MemberJoinEvent',
    'memberLeft'           => 'LINE\LINEBot\Event\MemberLeaveEvent',
    'things'               => 'LINE\LINEBot\Event\ThingsEvent',
  ];

  public static $messageMap    = [
    'text'                       => 'LINE\LINEBot\Event\MessageEvent\TextMessage',
    'image'                      => 'LINE\LINEBot\Event\MessageEvent\ImageMessage',
    'video'                      => 'LINE\LINEBot\Event\MessageEvent\VideoMessage',
    'audio'                      => 'LINE\LINEBot\Event\MessageEvent\AudioMessage',
    'file'                       => 'LINE\LINEBot\Event\MessageEvent\FileMessage',
    'location'                   => 'LINE\LINEBot\Event\MessageEvent\LocationMessage',
    'sticker'                    => 'LINE\LINEBot\Event\MessageEvent\StickerMessage',
  ];

  public static function parseEventRequest($parsedEvents, $eventsOnly = true): array
  {

    $events = [];

    foreach ($parsedEvents['events'] as $eventData) {

      $eventType = $eventData['type'];

      if (!isset(self::$eventMap[$eventType])) {
        # Unknown event has come
        $lineEvent = new UnknownEvent($eventData);

      } else {

        $eventClass = self::$eventMap[$eventType];

        $lineEvent = ($eventType === 'message') ? self::parseMessageEvent($eventData) : new $eventClass($eventData);

      }

      $events[] = new MessageEvent($lineEvent);
    }

    if ($eventsOnly) {

      return $events;
      
    }

    return [$parsedEvents['destination'], $events];
  }

  /**
   * @param array $eventData
   * @return MessageEvent
   */
  private static function parseMessageEvent($eventData)
  {
    $messageType = $eventData['message']['type'];

    if (!isset(self::$messageMap[$messageType])) {

      return new UnknownMessage($eventData);

    }

    $messageClass = self::$messageMap[$messageType];
    
    return new $messageClass($eventData);
  }
}
