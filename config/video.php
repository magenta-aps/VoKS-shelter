<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2019 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
    'do_recording' 	=> env('VIDEO_DO_RECORDING', false),
    'base_url' => env('VIDEO_BASE_URL', 'http://localhost:8080/'),
    'endpoints'     => [
            'start' => env('VIDEO_START_ENDPOINT', 'start'),
            'stop' => env('VIDEO_STOP_ENDPOINT', 'stop')
        ]
];
