<?php

return [
    'limit' => 40, //how many lines to show from the end of the file
    'websocket' => [
      'access' => env('WEBSOCKET_LOG_OUTPUT', '/var/log/ws-server.output.log'),
      'error' => env('WEBSOCKET_LOG_ERROR', 'var/log/ws-server.error.log')
    ],
    'http' => [
      'access' => env('HTTP_LOG_ACCESS', '/var/log/apache2/access.log'),
      'error' => env('HTTP_LOG_ERROR', '/var/log/apache2/error.log')
    ]
];