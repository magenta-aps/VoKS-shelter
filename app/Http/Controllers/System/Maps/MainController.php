<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System\Maps;

use BComeSafe\Http\Controllers\System\BaseController;
use BComeSafe\Models\Campus;
use BComeSafe\Packages\Aruba\Airwave\Importer\Import;
use BComeSafe\Packages\Aruba\Airwave\Structure;

class MainController extends BaseController
{
    public function getIndex()
    {
        return view('system.maps.index');
    }

    public function getSync()
    {
        (new Import())->structure();
    }

    public function getList()
    {
        return Campus::with('buildings.floors.image')->orderBy('campus_name', 'asc')->get();
    }
}
