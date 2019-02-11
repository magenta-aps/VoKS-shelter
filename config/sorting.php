<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
    'options' => [
        "-profile['name']" => 'order.name.descending',
        "profile['name']" => 'order.name.ascending',
        "-messages['unread']" => 'order.messages.descending',
        "messages['unread']" => 'order.messages.ascending',
        "-timestamps['lastActive']" => 'order.activity.descending',
        "timestamps['lastActive']" => 'order.activity.ascending'
    ],
    'default' => "-profile['name']"
];