<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\Device;
use BComeSafe\Models\Floor;
use BComeSafe\Packages\Coordinates\Coordinates;
use BComeSafe\Packages\Cisco\Cmx\Location\CmxLocation;
use GuzzleHttp\Promise;
use Illuminate\Console\Command;

/**
 * Class CiscoSyncActive
 *
 * @package BComeSafe\Console\Commands
 */
class CiscoSyncDetails extends Command
{
    const MAX_ALLOWED_RUNS = 999;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cisco:sync:details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Cisco clients details';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //Coordinates
        $full_time_start = $time_start = microtime(true);
        $clients = CmxLocation::getAllCoordinates();        
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;

        $count = count($clients);
        echo "Count (Cisco clients total): ", $count, PHP_EOL;
        
        //Floors
        $floors = Floor::with('image')->get()->toArray();
        $floors = array_map_by_key($floors, 'floor_hash_id');

        echo "*****************", PHP_EOL;
        if (!empty($clients)) {
          //Filter Clients by Floors
          foreach($clients as $k => $client) {
            if (!isset($floors[$client['floor_id']])) {
              unset($clients[$k]);
            }
            else {
              $clients[$k] = $this->mapClientModel($floors, $client);
            }
          }
          $count = count($clients);
          echo "*****************", PHP_EOL;
          echo "Count (clients) after Floor filtering: ", $count, PHP_EOL;

          $i = 1;
          foreach ($clients as $l) {
            print_r($l) . PHP_EOL;
            if ($i >= 3) break;
            $i++;
          }
          echo "...", PHP_EOL;
        }
        else {
          echo "No Clients found!", PHP_EOL;
        }

        echo "Time for geting data Cisco API -> Clients: ", $execution_time, PHP_EOL;
        echo "************", PHP_EOL;

        //BCS devices
        $devices = Device::get(['mac_address','floor_id','school_id','fullname']);
        echo "Count (BCS Database all): ", count($devices), PHP_EOL;
        echo "************", PHP_EOL;

        if (!empty($devices) && !empty($clients)) {
          $devices_active = $devices->toArray();
          foreach ($clients as $loc) {
            $mac[$loc['mac_address']] = $loc['mac_address'] . " | " . $loc['username'];
          }

          foreach($devices_active as $a) {
            if(isset($mac[$a['mac_address']])) {
              unset($mac[$a['mac_address']]);
            }
          }
          echo "Devices CMX (active) - to be inserted (count): ", count($mac), PHP_EOL;
          echo "Devices CMX (active) - to be updated (count): ", ($count - count($mac)), PHP_EOL;

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
        echo "*       Roles               *", PHP_EOL;
        echo "*****************************", PHP_EOL;
        $roles = \DB::select("SELECT COUNT(*) AS `count`, `role`, username FROM devices GROUP BY `role` ORDER BY `count` DESC;");
        foreach($roles as $r) {
          echo $r->role, " | " . $r->count, PHP_EOL;
        }
        echo "*****************************", PHP_EOL;
        echo "*       Trigger Alarm       *", PHP_EOL;
        echo "*****************************", PHP_EOL;
        $trigger_status = \DB::select("select mac_address, fullname, device_type, username, role, x,y, school_id from devices where trigger_status = 1;");
        foreach($trigger_status as $t) {
          echo $t->school_id, " | ", $t->mac_address, " | ", $t->fullname, ' | ', $t->device_type, ' | ', $t->username, ' | ', $t->role, ' | x=', $t->x, ';y=', $t->y, PHP_EOL;
        }
        echo "*****************************", PHP_EOL;
        echo "*   Special devices info    *", PHP_EOL;
        echo "*****************************", PHP_EOL;
        $spec_devices_mac = array(
          'F8:CA:B8:64:B7:F3',
          '88:07:4B:B0:2A:50',
          'a0:d7:95:b6:45:69'
        );
        $spec_devices = \DB::select("select mac_address, fullname, device_type, username, role, x, y, school_id from devices where mac_address in (
          '".implode("','",$spec_devices_mac)."'
		);
		");
        foreach($spec_devices as $t) {
          echo $t->school_id, " | ", $t->mac_address, " | ", $t->fullname, ' | ', $t->device_type, ' | ', $t->username, ' | ', $t->role, ' | x=', $t->x, ';y=', $t->y, PHP_EOL;
        }

        echo "*****************************", PHP_EOL;
        echo "* Special devices info Cisco *", PHP_EOL;
        echo "*****************************", PHP_EOL;
        foreach($spec_devices_mac as $m) {
          $loc = CmxLocation::getCoordinates($m);
          if (!empty($loc['mac_address'])) {
            $floor = !empty($loc['floor_id']) ? 'yes' : 'no';
            echo $loc['mac_address'], " | floor=", $floor, " | x=", $loc['x'], ";y=", $loc['y'], PHP_EOL;
          }
          else {
            echo $m, " | NOT FOUND!", PHP_EOL;
          }
        }

        $full_time_end = microtime(true);
        $execution_time = $full_time_end - $full_time_start;
        echo "Execution time Total: ", $execution_time, PHP_EOL;
    }

        /**
     * @param $floors
     * @param $client_cisco
     * @return array
     */
    protected function mapClientModel($floors, $client_cisco)
    {
        $floor = $floors[$client_cisco['floor_id']];

        $client['x'] = $client_cisco['x'];
        $client['y'] = $client_cisco['y'];
        $client['floor_id'] = $floors[$client_cisco['floor_id']]['id'];
        $client['school_id'] = $floors[$client_cisco['floor_id']]['school_id'];
        $client['mac_address'] = $client_cisco['mac_address'];
        $client['username'] = $client_cisco['username'];

        $client = array_merge_filled(
            $client,
            Coordinates::convert(
                $floor['image']['pixel_width'],
                $floor['image']['real_width'],
                $floor['image']['pixel_height'],
                $floor['image']['real_height'],
                $client['x'],
                $client['y']
            )
        );

        return $client;
    }
}
