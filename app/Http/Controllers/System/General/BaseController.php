<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System\General;

use BComeSafe\Libraries\Menus;

class BaseController extends \BComeSafe\Http\Controllers\System\BaseController
{
    public function __construct()
    {
        parent::__construct();

        \View::share('generalMenu', (new Menus())->getMenu('system'));
    }
}
