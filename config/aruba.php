<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

$airwave = env('ARUBA_AIRWAVE_URL');
$clearpass = env('ARUBA_CLEARPASS_URL');

return [
    'ale'       => [
        'enabled'                   => env('ARUBA_ALE_ENABLED', true),
        'baseUrl'                   => env('ARUBA_ALE_URL'),
        'username'                  => env('ARUBA_ALE_USERNAME'),
        'password'                  => env('ARUBA_ALE_PASSWORD'),
        'apiUrl'                    => '/api/v1',
        'coordinatesEnabled'        => env( 'ARUBA_COORDINATES_ENABLED', true ),
        'coordinatesExpirationTime' => env('COORDINATES_EXPIRATION_TIME', 5),
        'coordinatesTimeInterval'   => env('COORDINATES_TIME_INTERVAL', 3600 * 8)
    ],
    'airwave'   => [
        'enabled'  => env('ARUBA_AIRWAVE_ENABLED', true),
        'url'      => $airwave . '/',
        'login'    => [
            'url'      => $airwave . '/LOGIN',
            'username' => env('ARUBA_AIRWAVE_USERNAME'),
            'password' => env('ARUBA_AIRWAVE_PASSWORD')
        ],
        'campuses' => [
            'url' => $airwave . '/visualrf/campus.xml?buildings=1&sites=1&images=1&aps=1',
        ],
        'sites'    => [
            'url' => $airwave . '/visualrf/site.xml?images=1'
        ]
    ],
    'clearpass' => [
        'enabled' => env('ARUBA_CLEARPASS_ENABLED', true),
        'login'   => [
            'url'      => '',
            'username' => env('ARUBA_CLEARPASS_USERNAME'),
            'password' => env('ARUBA_CLEARPASS_PASSWORD')
        ],
        'user'    => [
            'profile' => $clearpass . '/tipsapi/config/read/Endpoint',
            'device'  => $clearpass . '/async_netd/deviceprofiler/endpoints/'
        ]
    ],
    'cookies'   => [
        'airwave' => storage_path('app/airwave-cookies')
    ],
    'google'    => [
    	'enabled'   => env( 'GOOGLE_MAPS_ENABLED', true ),
    	'maps_key'  => env( 'GOOGLE_MAPS_KEY' ),
    ],
    'cisco'    => [
	    'enabled'   => env( 'CISCO_ENABLED', true ),
    ],
];
