<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System;

use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Libraries\Menus;

/**
 * Class ConfigurationController
 * @package BComeSafe\Http\Controllers\Admin
 */
class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //        $this->middleware('system.language');

        \View::share('nav', (new Menus())->getMenu('main'));
        \View::share('config', \Shelter::getConfig());
    }
}
