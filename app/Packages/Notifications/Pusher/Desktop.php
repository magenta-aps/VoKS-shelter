<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Notifications\Pusher;

use BComeSafe\Packages\Websocket\ShelterClient;

/**
 * Class Desktop
 *
 * @package BComeSafe\Packages\Notifications\Pusher
 */
class Desktop extends BasePusher
{

    /**
     * @type string
     */
    protected $type = 'desktop';

    /**
     * @var
     */
    protected $payload;

    /**
     * @var
     */
    protected $message;

    /**
     * @param $message
     *
     * @return array
     */
    public function prepareMessage($message)
    {
        return [
            "title" => "Crisis team message",
            "body" => $message['message'],
            'id' => $message['id'],
            "timestamp" => round(microtime(true) * 1000)
        ];
    }

    /**
     * @param $message
     */
    public function pushOut($message)
    {
        $socket = new ShelterClient(config('alarm.php_ws_url').'/'.config('alarm.php_ws_client'));
        $socket->pushMessages($this->devices, $message);
    }
}
