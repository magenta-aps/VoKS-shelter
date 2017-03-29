<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Websocket;

use BComeSafe\Packages\Websocket\Messages\MessageInterface;

/**
 * Class Client
 *
 * Websocket connection instance
 *
 * @package BComeSafe\Packages\Websocket
 */
class Client
{
    /**
     * Websocket server url
     *
     * @var  $url
     * @type string
     */
    protected $url;

    /**
     * Socket connection
     *
     * @var  $socket
     * @type \WebSocket\Client
     */
    protected $socket;

    /**
     * Creates a websocket client instance
     *
     * @param $url - full url to the websocket server
     */
    public function __construct($url)
    {
        $context = stream_context_create();
        stream_context_set_option($context, 'ssl', 'verify_peer', false);
        stream_context_set_option($context, 'ssl', 'verify_host', false);
        stream_context_set_option($context, 'ssl', 'allow_self_signed', true);

        $options = [
            'context' => $context
        ];

        $this->socket = new \WebSocket\Client($url, $options);
    }

    /**
     * Sends a message to the websocket server
     *
     * @param MessageInterface $message
     */
    public function sendMessage(MessageInterface $message)
    {
        $this->socket->send($message->getMessage());
    }

    /**
     * Closse the websocket connection on object destruction
     */
    public function __destruct()
    {
        $this->socket->close();
    }
}
