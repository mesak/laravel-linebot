<?php

return [
    'line' => [
        'client_id' => env('LINE_CLIENT_ID'),
        'client_secret' => env('LINE_CLIENT_SECRET'),
    ],
    'listener' => 'Mesak\LineBot\Listener\LineBotMessage',
    'event_parser' => 'Mesak\LineBot\Events\EventRequestParser'
];
