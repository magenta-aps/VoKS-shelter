<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SchoolDefaultFields;
use BComeSafe\Packages\Aruba\Airwave\Importer\AirwaveImport;
use Illuminate\Console\Command;

class SyncAps extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sync:aps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Aps';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      
      $default = SchoolDefault::getDefaults();
      //Sync infrastructure
      if (!empty($default->client_data_source)) {
        //Aruba Airwave
        if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA && config('aruba.airwave.enabled')) {
          (new AirwaveImport())->structureAps();
        }
      }
    }
}
