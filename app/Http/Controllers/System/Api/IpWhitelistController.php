<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System\Api;

use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Http\Requests;
use BComeSafe\Libraries\SchoolIpHandler;

/**
 * Class IpWhitelistController
 *
 * @package  BComeSafe\Http\Controllers
 */
class IpWhitelistController extends Controller
{
    /**
     *  Setup IP Handler instance
     */
    public function __construct()
    {
        $this->ipHandler = new SchoolIpHandler();
    }

    /**
     * @param $ipAddress
     * @param $schoolId
     * @return array
     */
    public function getCheck($ipAddress, $schoolId)
    {
        return $this->ipHandler->check($ipAddress, $schoolId);
    }

    /**
     * @return static
     */
    public function getList()
    {
        return $this->ipHandler->getMappedList();
    }
}