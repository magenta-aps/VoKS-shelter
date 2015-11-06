<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Notifications;

class Item
{
    protected $item;
    
    public function __construct($message, $id)
    {
        $this->item = array('message' => $message, 'id' => $id);
    }
    
    public function getMessage()
    {
        return $this->item;
    }
}
