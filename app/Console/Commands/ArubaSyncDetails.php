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
        $stations = Location::getStations();
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        $count = count($stations);

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
          foreach ($stations as $s) {
            $mac[$s['mac_address']] = $s['role'] . " | " . $s['username'];
          }

          foreach($devices_active as $a) {
            if(isset($mac[$a['mac_address']])) {
              unset($mac[$a['mac_address']]);
            }
          }
          echo "Devices ALE (active) - to be inserted (count): ", count($mac), PHP_EOL;
          echo "Devices ALE (active) - to be updated (count): ", ($count - count($mac)), PHP_EOL;

          $i=1;
          echo "Devices ALE (active) - to be inserted: ", PHP_EOL;
          foreach($mac as $k => $m) {
            echo $k, ' -> ' . $m, PHP_EOL;
            $i++;
            if ($i >= 10) break;
          }
          echo "...", PHP_EOL;
        }
        echo "*****************************", PHP_EOL;
        echo "*       Roles               *:", PHP_EOL;
        echo "*****************************", PHP_EOL;
        $roles = \DB::select("SELECT COUNT(*) AS `count`, `role`, username FROM devices GROUP BY `role` ORDER BY `count` DESC;");
        foreach($roles as $r) {
          echo $r->role, " | " . $r->count, PHP_EOL;
        }
        echo "*****************************", PHP_EOL;
        echo "*       Trigger Alarm       *:", PHP_EOL;
        echo "*****************************", PHP_EOL;
        $trigger_status = \DB::select("select mac_address, fullname, device_type, username, role, x,y, school_id from devices where trigger_status = 1;");
        foreach($trigger_status as $t) {
          echo $t->school_id, " | ", $t->mac_address, " | ", $t->fullname, ' | ', $t->device_type, ' | ', $t->username, ' | ', $t->role, ' | x=', $t->x, ';y=', $t->y, PHP_EOL;
        }
        echo "*****************************", PHP_EOL;
        echo "*   Special devices info    *:", PHP_EOL;
        echo "*****************************", PHP_EOL;
        $spec_devices = \DB::select("select mac_address, fullname, device_type, username, role, x, y, school_id from devices where mac_address in (
			'88:53:2E:E9:C7:35',
			'64:BC:0C:83:E3:40',
			'E8:B4:C8:A7:5E:E7',
			'D4:67:C8:D8:E0:56',
			'00:34:2F:3F:39:26'
		);
		");
        foreach($spec_devices as $t) {
          echo $t->school_id, " | ", $t->mac_address, " | ", $t->fullname, ' | ', $t->device_type, ' | ', $t->username, ' | ', $t->role, ' | x=', $t->x, ';y=', $t->y, PHP_EOL;
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
