<?php

return [
    'limit' => 40, //how many lines to show from the end of the file
    'websocket' => [
        'access' => env('WEBSOCKET_LOG', '/var/log/ws-server.out.log')
    ],
    'apache' => [
        'access' => env('APACHE_LOG', '/var/log/apache2/access_log')
    ]
];