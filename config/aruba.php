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
    'enabled'  => env('ARUBA_ENABLED', false),
    'roles' => [
      'remove' => env('ARUBA_ROLES_REMOVE', '')
    ],
    'ale'       => [
        'enabled'  => env('ARUBA_ALE_ENABLED', false),
        'aleServersCount' => env('ARUBA_ALE_SERVERS_COUNT', 1),
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
        'enabled'  => env('ARUBA_AIRWAVE_ENABLED', true),
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
        ],
        'aps' => [
            'url' => $airwave . '/visualrf/access_point.xml',
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
        'airwave' => storage_path('app/airwave-cookies'),
        'controller' => storage_path('app/controller-cookies')
    ],
    'controllers' => [
        'enabled' => env('ARUBA_CONTROLLERS_ENABLED', true),
        'port' => env('ARUBA_CONTROLLERS_PORT', 4343),
        'login' => [
            'url' => '/v1/api/login',
            'username' => env('ARUBA_CONTROLLERS_USERNAME'),
            'password' => env('ARUBA_CONTROLLERS_PASSWORD'),
        ],
        'logout' => [
            'url' => '/v1/api/logout'
        ],
        'devices' => [
            'url' => '/v1/configuration/showcommand',
        ],
        'key' => env('ARUBA_CONTROLLERS_KEY')
    ]
];