<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers;

use BComeSafe\Packages\Configuration\Shelter;

class ShelterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //		$this->middleware('auth');
        $this->middleware('admin.access');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function getIndex()
    {
        $shelter = new Shelter();
        $config = $shelter->getConfig();

        // View
        return view(
            'shelter',
            [
            'config' => $config
            ]
        );
    }
}
