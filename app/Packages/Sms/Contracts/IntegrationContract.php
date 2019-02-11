<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Sms\Contracts;

/**
 * Integration interface
 */
interface IntegrationContract
{
    /**
     * Send a text (SMS) message to the phone provided
     *
     * @param integer $phone   Phone number
     * @param string  $message Message contents
     */
    public function sendMessage($phone, $message);
}
