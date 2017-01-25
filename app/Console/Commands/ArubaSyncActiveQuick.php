<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\Device;
use BComeSafe\Packages\Aruba\Ale\Location;
use GuzzleHttp\Promise;
use Illuminate\Console\Command;

/**
 * Class ArubaSyncActive
 *
 * @package BComeSafe\Console\Commands
 */
class ArubaSyncActiveQuick extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aruba:sync:active_quick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update active Aruba clients (quick)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Get from ALE
        $full_time_start = $time_start = microtime(true);
        $macAddresses = Location::getStations();
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Time for geting active devices from ALE: ", $execution_time, PHP_EOL;
        $count = count($macAddresses);
        echo "Devices ALE (active): ", $count, PHP_EOL;

        $devices = Device::get(['mac_address','floor_id','school_id','fullname']);
        echo "Devices DB (all): ", count($devices), PHP_EOL;
        if ($devices) {
          $devices_active = $devices->toArray();
          $mac = array_flip($macAddresses);
/*


          //
          $i=1;
          foreach($devices_active as $a) {
            echo "Device DB (active): ", $a['fullname'] . ' ' . $a['mac_address'] . ' ' . $a['floor_id'] . ' ' . $a['school_id'], PHP_EOL;
            $i++;
            if($i>=10) break;
          }
*/
          foreach($devices_active as $a) {
            if(isset($mac[$a['mac_address']])) {
              unset($mac[$a['mac_address']]);
            }
          }

          //
          $i=1;
          foreach($mac as $k => $m) {
            echo "Device ALE (active): ", $k .' = '. $m, PHP_EOL;
            $i++;
            if($i>=20) break;
          }

          echo "Devices ALE (active) - to be inserted: ", count($mac), PHP_EOL;
          echo "Devices ALE (active) - to be updated: ", ($count - count($mac)), PHP_EOL;


        }

        /*
        $time_start = microtime(true);
        //Device::updateActiveClientsQuick($macAddresses);
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Execution time for updating DB (active) (quick): ", $execution_time, PHP_EOL;
        */
        $full_time_end = microtime(true);
        $execution_time = $full_time_end - $full_time_start;
        echo "Execution time for ALL (quick): ", $execution_time, PHP_EOL;
    }
}
