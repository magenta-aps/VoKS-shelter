<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
  'enabled'       => env('CISCO_ENABLED', true),
  'baseUrl'       => env('CISCO_URL', ''),
  'username'      => env('CISCO_USERNAME', ''),
  'password'      => env('CISCO_PASSWORD', ''),
  'api' => [
    'campuses'    => '/api/config/v1/maps',
    'images'      => '/common/data/floormaps',
    'clients'     => '/api/location/v3/clients'
  ],
  'campusesList'  => env('CISCO_CAMPUSES_LIST', ''),
  'clientsDebug'  => env('CISCO_CLIENTS_DEBUG', '')
];