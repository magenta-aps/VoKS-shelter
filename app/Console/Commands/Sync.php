<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\CrisisTeamMember;
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SchoolDefaultFields;
use BComeSafe\Packages\Aruba\Airwave\Importer\AirwaveImport;
use BComeSafe\Packages\Cisco\Cmx\Importer\CmxImport;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class Sync extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync crisis team members, campuses, floors and maps';

    /**
      * Get the console command arguments.
      *
      * @return array
      */
    protected function getArguments()
    {
      return [
        ['skip', InputArgument::OPTIONAL, 'Skip Crisis Team members or Images download or Both'],
      ];
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      
      $skip_ctm = FALSE;
      $skip = $this->argument('skip');
      if (!empty($skip)) {
        //Skip Crisis Team members sync
        if ($skip == 1) {
          $skip_ctm = TRUE;
        }
        //Skip Image download
        if ($skip == 2) {
          $skip_images = TRUE;
        }
        //Skip Both
        if ($skip == 3) {
          $skip_ctm = TRUE;
          $skip_images = TRUE;
        }
      }
      
      $default = SchoolDefault::getDefaults();
      //Sync crisis team members
      if (empty($skip_ctm)) {
        if (!empty($default->user_data_source)) {
          //Active Directory
          if ($default->user_data_source == SchoolDefaultFields::USER_DATA_SOURCE_AD && config('ad.enabled')) {
            CrisisTeamMember::sync();
          }
        }
      }
      
      //Sync infrastructure
      if (!empty($default->client_data_source)) {
        //Aruba Airwave
        if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA && config('aruba.airwave.enabled')) {
          (new AirwaveImport())->structure($skip_images);
        }
        //Cisco CMX
        if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO && config('cisco.enabled')) {
          (new CmxImport())->structure($skip_images);
        }
      }
    }
}
