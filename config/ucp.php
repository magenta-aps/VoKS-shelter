<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
    'enabled' => env('UCP_ENABLED', false),
    /**
     * Client debugging
     */
    'debug' => false,

    /**
     * Authentication details
     *
     * Username and password that are being sent as parameters
     * to AuthenticationManager.login UCP API method.
     */
    'username'      => env('UCP_USERNAME'),
    'password'      => env('UCP_PASSWORD'),

    /**
     * HttpClient configuration
     */
    'httpClient' => [
        'debug'         => false,

        'base_uri'      => env('UCP_BASE_URI'),

        /**
         * Digest access authentication.
         * Used when generating md5 authorization hash.
         *
         * @link https://en.wikipedia.org/wiki/Digest_access_authentication
         */
        'digest' => [
            'username' => env('UCP_API_USER'),
            'password' => env('UCP_API_PASS'),

            'realm'    => null,
            'uri'      => null,
            'nonce'    => null
        ]
    ]
];
