<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Notifications\Server;

    use BComeSafe\Packages\Notifications;

    /**
     * Class Server
     *
     * @package BComeSafe\Packages\Notifications\Server
     */
class Server
{

    /**
         * @type array
         */
    protected $pushers = [];
    /**
         * @type string|\BComeSafe\Packages\Notifications\Item
         */
    protected $message = '';

    /**
         * @param \BComeSafe\Packages\Notifications\Item $message
         */
    public function __construct(Notifications\Item $message)
    {
        date_default_timezone_set("UTC");

        $this->message = $message;
    }

    /**
         * @param \BComeSafe\Packages\Notifications\Pusher\PusherInterface $device
         */
    public function registerDevicePusher(Notifications\Pusher\PusherInterface $device)
    {
        $this->pushers[] = $device;
    }

    /**
         * @param array $devices
         *
         * @return array
         */
    public function send(Array $devices = [])
    {
        $deviceCount = count($devices);
        $groups = [];

        // group devices by device type to send the notifications in bulk
        // id is gcm id
        // type is ios|android|desktop

        for ($i = 0; $i < $deviceCount; $i++) {
            $groups[$devices[$i]['type']][] = $devices[$i]['id'];
        }

        $response = [];

        for ($i = 0; $i < count($this->pushers); $i++) {
            $type = $this->pushers[$i]->getType();
            if (isset($groups[$type])) {
                // loop through the grouped devices and add them to the recipient list
                for ($a = 0; $a < count($groups[$type]); $a++) {
                    $this->pushers[$i]->addDevice($groups[$type][$a]);
                }

                // try and send it through
                try {
                    $result = $this->pushers[$i]->processMessages($this->message->getMessage());
                    $response[] = $result;
                } catch (\Exception $e) {
                    $response[] = $e->getMessage();
                }
            }
        }

        return $response;
    }
}
