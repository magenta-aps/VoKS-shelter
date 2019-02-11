<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Notifications\Pusher;

abstract class BasePusher implements PusherInterface
{

    protected $options = array();
    protected $devices = array();
    
    protected $type = ''; //used by the server to group devices
    
    public function __construct(Array $options = array())
    {
        $this->options = $options;
    }

    public function addDevice($device)
    {
        $this->devices[] = $device;
        return $this;
    }
    
    public function processMessages($text)
    {
        $message = $this->prepareMessage($text);
        $response = $this->pushOut($message);
        
        return $response;
    }
    
    final public function getType()
    {
        return $this->type;
    }
    
    abstract public function prepareMessage($message);
    abstract public function pushOut($message);
}
