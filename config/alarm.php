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
    'default_id' => env('SCHOOL_ID', null),
    'default_bcs' => array(
      'bcs_id' => env('BCS_ID', null),
      'bcs_name' => env('BCS_NAME', null),
      'bcs_url' => env('BCS_URL', null),
      'police_number' => env('BCS_POLICE_NUMBER', null),
      'public' => 0
    ),
    'activity_timeout' => env('ACTIVITY_TIMEOUT', 10),
    'url' => env('SHELTER_URL'),
    'php_ws_url' => !env('SSL_ON', false)
        ? 'ws://' . env('PHP_WS_URL', '127.0.0.1') . ':9000'
        : 'wss://' . env('PHP_WS_URL', '127.0.0.1') . ':9001',
    'php_ws_client' => 'php-client',
    'coordinatesEnabled'        => env('COORDINATES_ENABLED', true),
    'coordinatesExpirationTime' => env('COORDINATES_EXPIRATION_TIME', 5), //@Todo - check where it is used and remove if not needed.
    'coordinatesTimeInterval'   => env('COORDINATES_TIME_INTERVAL', 3600 * 8) //@Todo - check where it is used and remove if not needed.
];