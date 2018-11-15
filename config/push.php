<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
    'android' => [
        'endpoint_url' => 'https://android.googleapis.com/gcm/send',
        'api_key'      => env('ANDROID_API_KEY', ''),
    ],
    'ios'     => [
        'endpoint_url'     => 'ssl://gateway.push.apple.com:2195',
        'password'         => env('IOS_PUSH_PASSWORD', ''),
        'certificate_path' => app_path() . '/../certificates/apns@181115.pem',
        'action_loc_key'   => '',
        'expiry'           => env('IOS_PUSH_EXPIRY', 3600),

        'production'       => [
            'endpoint_url'     => 'ssl://gateway.push.apple.com:2195',
            'password'         => env('IOS_PUSH_PASSWORD', ''),
            'certificate_path' => app_path() . '/../certificates/apns@181115.pem',
            'action_loc_key'   => '',
            'expiry'           => env('IOS_PUSH_EXPIRY', 3600)
        ],
        'dev'              => [
            'endpoint_url'     => 'ssl://gateway.sandbox.push.apple.com:2195',
            'password'         => env('IOS_PUSH_PASSWORD', ''),
            'certificate_path' => app_path() . '/../certificates/apns@181115.pem',
            'action_loc_key'   => '',
            'expiry'           => env('IOS_PUSH_EXPIRY', 3600)
        ]
    ]
];
