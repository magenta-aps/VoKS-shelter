<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Notifications\Pusher;

interface PusherInterface
{
    public function addDevice($id);
    public function prepareMessage($message);
    public function pushOut($message);
    public function processMessages($text);
}
