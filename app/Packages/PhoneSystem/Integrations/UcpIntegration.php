<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\PhoneSystem\Integrations;

use BComeSafe\Packages\PhoneSystem\Contracts\IntegrationContract as IntegrationContractInterface;
use UCP\Client;

/**
 * UCP integration
 */
class UcpIntegration implements IntegrationContractInterface
{
    /**
     * Integration system name
     *
     * @var string
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
    public function getNodes()
    {
        return $this->client->node()->getList();
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups($nodeId)
    {
        return $this->client->group()->getList($nodeId);
    }

    /**
     * {@inheritDoc}
     */
    public function getVoices($nodeId)
    {
        return $this->client->media()->getList($nodeId);
    }

    /**
     * {@inheritDoc}
     */
    public function play($nodeId, $voiceId, $groupId, $interruptId = 0, $repeat = 0)
    {
        return $this->client->broadcast()->play($nodeId, $voiceId, $groupId, $interruptId, $repeat);
    }

    /**
     * {@inheritDoc}
     */
    public function broadcast($nodeId, $phoneNumber, $groupId, $interruptId = 0, $repeat = 0, $live = false)
    {
        return $this->client->broadcast()->broadcast($nodeId, $phoneNumber, $groupId, $interruptId, $repeat, $live);
    }
}
