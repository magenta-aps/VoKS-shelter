<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\SchoolStatus;
use BComeSafe\Models\Device;
use BComeSafe\Models\History;
use BComeSafe\Models\GotItHistory;
use BComeSafe\Packages\Websocket\ShelterClient;
use Illuminate\Console\Command;

class ResetShelters extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'reset:shelters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rest all Shelters';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      
      $schools = School::where('status_alarm', '=', '1')->get()->toArray();
      if (empty($schools)) return;
      
      foreach($schools as $school_id) {
        //send out shelter reset message to all clients
        $websockets = new ShelterClient(config('alarm.php_ws_url') . '/' . config('alarm.php_ws_client'));
        $websockets->reset($school_id);

        // School status
        SchoolStatus::statusAlarm($school_id, 0);
        SchoolStatus::statusPolice($school_id, 0);

        // reset device statuses
        Device::updateAllToInactive($school_id);

        // clear history for the last alarm
        History::truncateForShelter($school_id);

        // clear got it history for the last alarm
        GotItHistory::truncateForShelter($school_id);
      }

      return;
    }
}
