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
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SchoolDefaultFields;
use BComeSafe\Packages\Coordinates\Coordinates;
use BComeSafe\Packages\Cisco\Cmx\Location\CmxLocation;
use BComeSafe\Packages\Aruba\Ale\AleLocation;
use GuzzleHttp\Promise;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SyncMacs extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sync:macs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update client coordinates by mac addresses';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      
        $default = SchoolDefault::getDefaults();
        if (!empty($default->client_data_source)) {
          //Aruba
          if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA) {
            //Aruba ALE
            if (config('aruba.ale.enabled') === false) {
              return;
            }
            else {
              $floors = Floor::with('image')->get()->toArray();
              $floors = array_map_by_key($floors, 'floor_hash_id');
            }
            //Aruba Controllers
            if (config('aruba.controllers.enabled') === false) {
              return;
            }
            else {
              $AurbaControllers = new ArubaControllers();
              $aps = Aps::get()->toArray();
              $aps = array_map_by_key($aps, 'ap_name');
            }
          }
          //Cisco CMX
          if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO) {
            if (config('cisco.enabled') === false) {
              return;
            }
            else {
              $floors = Floor::with('image')->get()->toArray();
              $floors = array_map_by_key($floors, 'floor_hash_id');
            }
          }
        } else {
          return;
        }
      
        $macs = $this->argument();
        array_shift($macs);

        $list = [];

        foreach ($macs as $mac) {
            if (!empty($mac)) {
                $list[] = $mac;
            }
        }
        $devices = Device::whereIn('mac_address', $list)->get(['id', 'mac_address']);
        if (empty($devices)) return;
        $updates = [];
        //
        foreach ($devices as $device) {
          //Aruba
          if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA) {
            //Aruba Ale
            if (config('aruba.ale.enabled')) {
              $location = AleLocation::getCoordinates($device->mac_address);
              if (!isset($floors[$location['floor_id']])) continue;
              $floor = $floors[$location['floor_id']];
              $client = $this->prepareClient($location, $floor);
              $updates[] = $client;
            }
            //Aruba Controllers
            if (config('aruba.controllers.enabled')) {
              $ap_name = $AurbaControllers->getAPByIp($device->ip_address, $device['school_id']);
              //Found AP name
              if (!empty($ap_name) && !empty($aps[$ap_name])) {
                //AP has changed
                if ($device->ap_name != $ap_name) {
                  $device['school_id'] = $aps[$ap_name]['school_id'];
                  $device['floor_id'] = $aps[$ap_name]['floor_id'];
                  $device['x'] = $aps[$ap_name]['x'];
                  $device['y'] = $aps[$ap_name]['y'];
                  $device['active'] = 1;
                  $updates[] = $client;
                }
              }
            }
          }
          //Cisco CMX
          if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO && config('cisco.enabled')) {
            $location = CmxLocation::getCoordinates($device->mac_address);
            if (!isset($floors[$location['floor_id']])) continue;
            $floor = $floors[$location['floor_id']];
            $client = $this->prepareClient($location, $floor);
            $updates[] = $client;
          }
        }
        
        //
        if (!empty($updates)) {
          Device::updateClientCoordinates($updates);
        }
    }
    
    public function prepareClient($location, $floor) {
      $client = array();
      $client['x'] = $location['x'];
      $client['y'] = $location['y'];
      $client['floor_id'] = $floor['id'];
      $client['school_id'] = $floor['school_id'];
      $client['mac_address'] = $location['mac_address'];
      $client['username'] = $location['username'];
      $client['fullname'] = $location['username'];

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
    
    public function getOptions()
    {
        return [
            ['active', null, InputOption::VALUE_OPTIONAL, 'Sync coordinates of currently active schools.', false]
        ];
    }

    protected function getArguments()
    {
        $args = [];
        for ($i=1; $i<20; $i++) {
            $args[] = ['mac:'.$i, InputArgument::OPTIONAL, 'Mac address'];
        }
        return $args;
    }
}
