<?php

namespace Mesak\LineBot\Listener;

// @see \LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\MessageEvent;
class LineBotMessage
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
    public function onMessage(MessageEvent $event)
    {
        $result = null;
        switch ($event->getMessageType()) {
            case  'text':
                $text = $event->getText();

                if ($text == 'hi') {
                    $result =  'Hello';
                } elseif ($text == 'cat') {
                    $result =  [
                        'type' => 'flex',
                        'altText' => '隨機貓咪',
                        'contents' => [
                            "type" => "bubble",
                            "hero" => [
                                "type" => "image",
                                "url" => "https://placekitten.com/245/180",
                                "size" => "full",
                                "aspectRatio" => "20:13",
                                "aspectMode" => "cover"
                            ],
                            "footer" => [
                                "type" => "box",
                                "layout" => "vertical",
                                "spacing" => "sm",
                                "contents" => [
                                    [
                                        "type" => "button",
                                        "style" => "link",
                                        "height" => "sm",
                                        "action" => [
                                            "type" => "uri",
                                            "label" => "Give Me Star",
                                            "uri" => "https://github.com/mesak/laravel-linebot"
                                        ]
                                    ]
                                ],
                                "flex" => 0
                            ]
                        ]
                    ];
                } elseif ($text == 'raw') {
                    $result =  [
                        'type' => 'flex',
                        'altText' => '隨機圖片',
                        'contents' => [
                            'type' => 'bubble',
                            'hero' => [
                                'type' => 'image',
                                'url' => 'https://placekitten.com/245/185',
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
