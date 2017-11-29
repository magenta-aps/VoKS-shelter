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
        'aleServersCount' => env('ARUBA_ALE_SERVERS_COUNT', 1),
        'coordinatesExpirationTime' => env( 'COORDINATES_EXPIRATION_TIME', 5 ),
        'coordinatesTimeInterval' => env( 'COORDINATES_TIME_INTERVAL', 3600 * 8 ),
        'aleServer1'       => [
            'baseUrl'   => env('ARUBA_ALE_URL1'),
            'username' => env('ARUBA_ALE_USERNAME1'),
            'password' => env('ARUBA_ALE_PASSWORD1'),
            'apiUrl'    => '/api/v1',
        ],
        'aleServer2'      => [
            'baseUrl'   => env('ARUBA_ALE_URL2'),
            'username' => env('ARUBA_ALE_USERNAME2'),
            'password' => env('ARUBA_ALE_PASSWORD2'),
            'apiUrl'    => '/api/v1'
        ],
        'aleServer3'      => [
            'baseUrl'   => env('ARUBA_ALE_URL3'),
            'username' => env('ARUBA_ALE_USERNAME3'),
            'password' => env('ARUBA_ALE_PASSWORD3'),
            'apiUrl'    => '/api/v1'
        ],
    ],
    'airwave'   => [
        'url'      => $airwave . '/',
        'login'    => [
            'url'      => $airwave . '/LOGIN',
            'username' => env('ARUBA_AIRWAVE_USERNAME'),
            'password' => env('ARUBA_AIRWAVE_PASSWORD')
        ],
        'campuses' => [
            'url' => $airwave . '/visualrf/campus.xml?buildings=1&sites=1&images=1',
        ],
        'sites'    => [
            'url' => $airwave . '/visualrf/site.xml?images=1'
        ]
    ],
    'clearpass' => [
        'login' => [
            'url'      => '',
            'username' => env('ARUBA_CLEARPASS_USERNAME'),
            'password' => env('ARUBA_CLEARPASS_PASSWORD')
        ],
        'user'  => [
            'profile' => $clearpass . '/tipsapi/config/read/Endpoint',
            'device'  => $clearpass . '/async_netd/deviceprofiler/endpoints/'
        ]
    ],
    'cookies'   => [
        'airwave' => storage_path('app/airwave-cookies')
    ]
];