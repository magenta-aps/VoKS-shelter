<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers;

use BComeSafe\Http\Requests;

class LoginCheckController extends Controller
{

    public function check()
    {
        $status= \Auth::check();
        return ['status' => $status];
    }
}
