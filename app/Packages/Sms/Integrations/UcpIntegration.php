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
class UcpIntegration implements IntegrationContractInterface
{
    /**
     * @var string Integration system name
     */
    protected $label = 'Ucp';

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
        // UCP Client
        $options = config('ucp');
        $this->client = new Client($options);

        // Authenticate
        $username = $options['username'];
        $password = $options['password'];
        $this->client->auth()->login($username, $password);
    }
    
    /**
     * {@inheritDoc}
     */
    public function sendMessage($phone, $message)
    {
        return $this->client->message()->send($phone, $message);
    }
}
