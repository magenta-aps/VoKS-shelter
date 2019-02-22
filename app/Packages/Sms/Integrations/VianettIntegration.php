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

class VianettIntegration implements IntegrationContract
{
    /**
     * @var string Integration system name
     */
    protected $label = 'Vianett';

    /**
     * {@inheritDoc}
     */
    public function sendMessage($phoneNumber, $message, $debug = false)
    {

        if (!config('sms.enabled') && !$debug) {
          return;
        }

        $curl = new CurlRequest();
        $curl->setUrl(
            config('sms.vianett.url'),
            [
            'src' => config('sms.from'),
            'dst' => $phoneNumber,
            'msg' => $message,
            'username' => config('sms.vianett.username'),
            'password' => config('sms.vianett.password')
            ]
        );
        return $curl->execute();
    }
}
