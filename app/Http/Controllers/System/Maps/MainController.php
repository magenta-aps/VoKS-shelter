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
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SchoolDefaultFields;
use BComeSafe\Packages\Aruba\Airwave\Importer\AirwaveImport;
use BComeSafe\Packages\Cisco\Cmx\Importer\CmxImport;

class MainController extends BaseController
{
    public function getIndex()
    {
        return view('system.maps.index');
    }

    public function getSync()
    {
      $default = SchoolDefault::getDefaults();
      //Sync infrastructure
      if (!empty($default->client_data_source)) {
        //Aruba Airwave
        if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA && config('aruba.airwave.enabled')) {
          (new AirwaveImport())->structure();
        }
        //Cisco CMX
        if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO && config('cisco.enabled')) {
          (new CmxImport())->structure();
        }
      }
    }

    public function getList()
    {
        return Campus::with('buildings.floors.image')->orderBy('campus_name', 'asc')->get();
    }
}
