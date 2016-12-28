<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Sms\Integrations;

use BComeSafe\Packages\Sms\Contracts\IntegrationContract as IntegrationContractInterface;
use UCP\Client;

/**
 * UCP integration implementation
 */
class ExampleIntegration implements IntegrationContractInterface
{
    /**
     * @var string Integration system name
     */
    protected $label = 'Example';

    /**
     * UCP Client
     *
     * @var Client
     */
    private $client;

    /**
     * Sets up UCP client and authenticates
     */
    public function __construct()
    {

    }
    
    /**
     * {@inheritDoc}
     */
    public function sendMessage($phone, $message)
    {
    }
}
