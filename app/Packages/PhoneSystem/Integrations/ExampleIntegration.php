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
class ExampleIntegration implements IntegrationContractInterface
{
    /**
     * Integration system name
     *
     * @var string
     */
    protected $label = 'Example';

    /**
     * Sets up UCP client and authenticates
     */
    public function __construct()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getNodes()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getGroups($nodeId)
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getVoices($nodeId)
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function play($nodeId, $voiceId, $groupId, $interruptId = 0, $repeat = 0)
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function broadcast($nodeId, $phoneNumber, $groupId, $interruptId = 0, $repeat = 0, $live = false)
    {
        return [];
    }
}
