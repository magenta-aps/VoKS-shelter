<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Api;

use BComeSafe\Http\Controllers\Controller;

/**
 * Class DemoController
 *
 * @package BComeSafe\Http\Controllers\Api
 */
class DemoController extends Controller
{
    /**
     * @return array
     */
    public function getArubaStructure()
    {
        return [];
    }
    
    /**
     * @return array
     */
    public function getArubaCoordinates()
    {
        return [];
    }
    
    /**
     * @return array
     */
    public function getCiscoCoordinates()
    {
        return [];
    }
}
