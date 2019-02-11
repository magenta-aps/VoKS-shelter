<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Sms\Integrations;

use BComeSafe\Libraries\CurlRequest;
use BComeSafe\Packages\Sms\Contracts\IntegrationContract;

class TalariaxIntegration implements IntegrationContract
{
    /**
     * @var string Integration system name
     */
    protected $label = 'Talariax';
    
    /**
     * {@inheritDoc}
     */
    public function sendMessage($phoneNumber, $message)
    {
        $curl = new CurlRequest();
        $curl->setUrl(
            config('sms.talariax.url'),
            [
            'tar_num' => $phoneNumber,
            'tar_msg' => $message,
            'tar_mode' => config('sms.talariax.mode'),
            ]
        );
        return $curl->execute();
    }
}
