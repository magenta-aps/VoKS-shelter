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
class ArubaSyncDetails extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aruba:sync:details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check active Aruba clients';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //ALE stations
        $full_time_start = $time_start = microtime(true);
        $macAddresses = Location::getStations();
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        $count = count($macAddresses);

        $macAddresses_other = Location::getStations(TRUE);
        echo "Date/Time: ", date("Y-m-d H:i:s", strtotime("+1 hour")), " CET", PHP_EOL;
        echo "Count (stations):", PHP_EOL;
        echo "With username: ", $count, PHP_EOL;
        echo "No username: ", count($macAddresses_other), PHP_EOL;
        echo "Total: ", (count($macAddresses_other)+$count), PHP_EOL;
        echo "Time for geting data from ALE API -> stations: ", $execution_time, PHP_EOL;
        echo "************", PHP_EOL;
        //ALE locations
        $time_start = microtime(true);
        $locations = Location::getAllCoordinates();
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;

        $count = count($locations);
        echo "Count (locations): ", $count, PHP_EOL;
        echo "Time for geting data ALE API -> locations: ", $execution_time, PHP_EOL;
        echo "************", PHP_EOL;

        //BCS devices
        $devices = Device::get(['mac_address','floor_id','school_id','fullname']);
        echo "Count (BCS Database all): ", count($devices), PHP_EOL;
        echo "************", PHP_EOL;

        if ($devices) {
          $devices_active = $devices->toArray();
          $mac = array_flip($macAddresses);

          foreach($devices_active as $a) {
            if(isset($mac[$a['mac_address']])) {
              unset($mac[$a['mac_address']]);
            }
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
        echo "Execution time Total: ", $execution_time, PHP_EOL;
    }
}
