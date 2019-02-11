<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\PhoneSystem;

use BComeSafe\Packages\Configuration\ComponentExtractor;

/**
 * Class SmsServiceManager
 * @package Sms
 */
class PhoneSystemServiceManager extends ComponentExtractor
{
    public function __construct()
    {
        $this->setDirectory(__DIR__.'/Integrations/');
        $this->setNamespace('PhoneSystem\\Integrations\\');
    }
}
