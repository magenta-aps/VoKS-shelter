<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Libraries\Menus;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('admin.access');

        \View::share('adminMenu', (new Menus())->getMenu('admin'));
        \View::share('id', \Shelter::getID());
        \View::share('config', \Shelter::getConfig());
    }
}
