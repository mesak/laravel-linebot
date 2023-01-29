<?php

namespace Mesak\LineBot\Listener;

// @see \LINE\LINEBot\Event\BaseEvent;
class SimpleListener
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

    public function onFollow(\LINE\LINEBot\Event\FollowEvent $event)
    {
        if ($event->isUserEvent()) {
            $userId = $event->getUserId();
            $bot = app(\Mesak\LineBot\Contracts\Bot::class);
            $userProfileResult = $bot->getProfile($userId);
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
    public function onMessage(\LINE\LINEBot\Event\MessageEvent $event)
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
                        'altText' => '請問要選擇哪一天?',
                        'contents' => [
                            'type' => 'bubble',
                            'hero' => [
                                'type' => 'image',
                                'url' => 'https://i.imgur.com/l8yNat5.jpg',
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
