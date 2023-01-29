<?php
return [
    'line' => [
        'client_id' => env('LINE_CLIENT_ID'),
        'client_secret' => env('LINE_CLIENT_SECRET'),
        'redirect' => env('LINE_REDIRECT_URI')
    ],
    'listener' => 'Mesak\LineBot\Listener\SimpleListener',
    'event_parser' => 'Mesak\LineBot\Events\EventRequestParser'
];
