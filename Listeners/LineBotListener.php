<?php

namespace App\Listeners;

// @see \LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\MessageEvent;
use Facades\Mesak\LineBot\Contracts\Bot as LineBot;

class LineBotListener
{

  /*
    onMessage
    onUnsend
    onFollow
    onUnfollow
    onJoin
    onLeave
    onPostback
    onVideoPlayComplete
    onBeacon
    onAccountLink
    onMemberJoined
    onMemberLeft
    onThings
    */

  public function onFollow(FollowEvent $event)
  {
    if ($event->isUserEvent()) {
      $userId = $event->getUserId();
      //$bot = app(\Mesak\LineBot\Contracts\Bot::class);
      //$userProfileResult = $bot->getProfile($userId);
      $userProfileResult = LineBot::getProfile($userId);
      if ($userProfileResult->successful()) {
        $user = $userProfileResult->json();
        $displayName = $user['displayName'];
        $pictureUrl = $user['pictureUrl'];
        $statusMessage = $user['statusMessage'];
        return $displayName  . ' Welcome to follow Mesak Robot';
      }
    }
    // return json_encode( $event->getEvent() );
    return 'Welcome to follow Mesak Robot';
  }
  public function onMessage(MessageEvent $event)
  {
    $result = null;
    switch ($event->getMessageType()) {
      case  'text':
        $text = $event->getText();
        if ($text == 'hi') {
          $result =  'Hello';
        } elseif ($text == 'raw') {
          $result =  [
            'type' => 'flex',
            'altText' => '隨機圖片',
            'contents' => [
              'type' => 'bubble',
              'hero' => [
                'type' => 'image',
                'url' => 'https://placedog.net/500/400',
                'size' => 'full',
                'aspectRatio' => '20:13',
                'aspectMode' => 'cover'
              ],
              //...flax message格式
            ]
          ];
        } else {
          $result =  $text;
        }
        break;
    }
    return $result;
  }
}
