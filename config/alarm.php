<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
    'notification' => [
        'limit' => 144
    ],
    'wol_port' => env('WOL_PORT', 7),
    'mode' => 1,
    'default' => [
        'mac' => env('MAC_ADDRESS', null)
    ],
    'secure' => env('SSL_ON', false),
    'default_id' => env('SCHOOL_ID', 1),
    'activity_timeout' => env('ACTIVITY_TIMEOUT', 10),
    'url' => env('SHELTER_URL'),
    'php_ws_url' => !env('SSL_ON', false)
        ? 'ws://'.env('PHP_WS_URL', '127.0.0.1'). ':9000'
        : 'wss://'.env('PHP_WS_URL', '127.0.0.1'). ':9001',
    'php_ws_client' => 'php-client'
];