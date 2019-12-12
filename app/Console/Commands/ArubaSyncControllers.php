<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\Device;
use BComeSafe\Models\School;
use BComeSafe\Models\Aps;
use BComeSafe\Models\Floor;
use BComeSafe\Packages\Coordinates\Coordinates;
use BComeSafe\Packages\Aruba\ArubaControllers\ArubaControllers;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ArubaSyncControllers
 *
 * @package  BComeSafe\Console\Commands
 */
class ArubaSyncControllers extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aruba:sync:controllers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update clients from Aruba Contollers';
    
    /**
     * All access points
     *
     * @var array
     */
    private $aps;
    
    /**
     * Debug
     *
     * @var array
     */
    private $debug;
    
    /**
      * Get the console command arguments.
      *
      * @return array
      */
    protected function getArguments()
    {
      return [
        ['school_id', InputArgument::OPTIONAL, 'Which School'],
        ['no_debug', InputArgument::OPTIONAL, 'No debug'],
        ['force', InputArgument::OPTIONAL, 'Force DB updated']
      ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
      if (!$this->aps) {
        $aps = Aps::all();
        if ($aps) {
          $this->aps = array_map_by_key($aps->toArray(), 'ap_name');
        }
      }
      //\Artisan::call('aruba:sync:controllers', ['school_id' => \Shelter::getID(), 'no_debug' => 1, 'force' => 1]);
      
      //*** Debug ***
      $this->debug = config('app.debug');
      $no_debug = $this->argument('no_debug');
      if ($no_debug) {
        $this->debug = FALSE;
      }
      
      if (!config('aruba.controllers.enabled')) {
        if ($this->debug) {
          echo 'Aruba Controllers disabled.';
          echo 'Finished', PHP_EOL;
        }
        return;
      }
      
      $schools = [];
      
      if ($this->debug) {
        echo 'Aps in DB: ' , count($this->aps), PHP_EOL;
      }
      
      //School ID
      $school_id = $this->argument('school_id');
      if ($this->debug) {
        echo "School ID: " , (!empty($school_id) ? $school_id : 'Parameter not found.'), PHP_EOL;
      }
      
      //Get Schools
      if (!empty($school_id)) {
        if ($this->debug) {
          echo 'Searching for school in BCS database...', PHP_EOL;
        }
        $school = School::find($school_id)->toArray();
        if (empty($school)) {
          if ($this->debug) {
            echo 'School not found', PHP_EOL;
            echo 'Finished', PHP_EOL;
          }
          return;
        }
        else {
          if ($this->debug) {
            echo 'Found school: ', $school['name'], PHP_EOL;
          }
        }
        $schools[$school_id] = $school;
      }
      else {
        if ($this->debug) {
          echo 'Searching for all schools in BCS database...', PHP_EOL;
        }
        $schools = School::whereNotNull('controller_url')->get()->toArray();
        if (empty($schools)) {
          if ($this->debug) {
            echo 'Schools not found', PHP_EOL;
            echo 'Finished', PHP_EOL;
          }
          return;
        }
        else {
          if ($this->debug) {
            echo 'Found schools: ', count($schools), PHP_EOL;
          }
        }
        $schools = array_map_by_key($schools, 'id');
      }
      
      if ($this->debug) {
        $full_time_start = microtime(true);
        $roles_remove = config('aruba.roles.remove');
        $roles_remove_arr = !empty($roles_remove) ? explode(',', $roles_remove) : [];
        echo 'Client with roles to be removed: ' . print_r($roles_remove_arr, true) . PHP_EOL;
      }
      
      //Start sync with controllers
      foreach ($schools as $school_id => $school) {
        if ($this->debug) {
          echo 'Updating school: ', $school['name'], ' (Controller: ', $school['controller_url'], ')', PHP_EOL;
        }
        $stats = $this->runUpdate($school);
      }
      
      if ($this->debug) {
        $full_time_end = microtime(true);
        $execution_time = $full_time_end - $full_time_start;
        $unique_roles = [];
        if (!empty($stats['unique_roles'])) {
          foreach($stats['unique_roles'] as $role => $r) {
            $unique_roles[$role] = 1;
          }
        }
        $unique_device_types = [];
        if (!empty($stats['unique_device_types'])) {
          foreach($stats['unique_device_types'] as $device_type => $d) {
            $unique_device_types[$device_type] = 1;
          }
        }
        echo "Found unique roles: " . print_r($unique_roles, true) . PHP_EOL;
        echo "Found unique device types: " . print_r($unique_device_types, true) . PHP_EOL;
        echo "Total execution time: ", $execution_time, PHP_EOL;
        echo 'Finished', PHP_EOL;
      }
    }

    /**
     * Get clients from Controller and update in BCS
     *
     * @param $school
     */
    public function runUpdate($school)
    {
        if ($this->debug) $time_start = microtime(true);
        $AurbaControllers = new ArubaControllers();
        
        //Get Clients
        $clients = $AurbaControllers->getClientsFromController($school['controller_url']);
        if ($this->debug) {
          $time_end = microtime(true);
          $execution_time = $time_end - $time_start;
          echo "Time for geting clients from Controller: ", $execution_time, PHP_EOL;
          $count = count($clients);
          echo "Found clients: ", $count, PHP_EOL;
        }
        
        if (empty($clients)) {
          if ($this->debug) {
            echo "Didn't found any clients.", PHP_EOL;
          }
          return;
        }
        
        if ($this->debug) {
          $time_start = microtime(true);
          echo 'Starting Clients update in DB', PHP_EOL, PHP_EOL;
        }
        
        $ret_val['unique_roles'] = [];
        $ret_val['unique_device_types'] = [];
        $ret_val['updated'] = 0;
        $ret_val['skipped'] = 0;
        //Update clients
        foreach($clients as $client) {
          if ($this->debug) {
            $ret_val['unique_roles'][$client['Role']] = 1;
            $ret_val['unique_device_types'][$client['Type']] = 1;
          }
          $status = $this->updateDevice($client);
          if ($status) {
            $ret_val['updated']++;
          }
          else {
            $ret_val['skipped']++;
          }
        }
        if ($this->debug) {
          $time_end = microtime(true);
          $execution_time = $time_end - $time_start;
          echo "Updated (clients): ", $ret_val['updated'], PHP_EOL;
          echo "Skipped (clients): ", $ret_val['skipped'], PHP_EOL;
          echo "Execution time for updating DB (clients) : ", $execution_time, PHP_EOL;
          echo "*****************", PHP_EOL;
        }
        
        return $ret_val;
    }
    
    /**
     * @param $client
     * @return boolean
     */
    public function updateDevice($client)
    {
      //Check Role
      $AurbaControllers = new ArubaControllers();
      if (!$AurbaControllers->checkClientRole($client['Role'])) return FALSE;
      
      $mac = strtoupper($client['MAC']);
      $ap_name = $client['AP name'];
      
      //Get Device from DB which has the same AP name
      $device = Device::where(['mac_address' => $mac, 'ap_name' => $ap_name])->first();
      //Found
      if ($device) {
        $device = $device->toArray();
        if ($this->debug) {
          //echo 'Found Device in DB with the same AP name: ' , $mac, ' (DB AP name:' . $device['ap_name'] . ')';
          //echo 'No need to update. Skipping.', PHP_EOL;
          $force = $this->argument('force');
          if (empty($force)) {
            return FALSE;
          }
        }
      }
      //Not found
      else {
        if ($this->debug) {
          //echo 'Not Found Device in DB with the same AP name: ' , $mac, ' (New AP name:' . $ap_name . ')';
          //echo 'Need to update. Updating.', PHP_EOL;
        }
      }
      
      //Update device
      $device = [];
      $device['mac_address'] = $mac;
      $device['active'] = 1;
      $device['ip_address'] = $client['IP'];
      $device['username'] = $client['Name'];
      $device['role'] = $client['Role'];
      $device['ap_name'] = $ap_name;
      if (!empty($this->aps[$ap_name])) {
        $device['x'] = Device::generateApproximateCoordinatesAroundAP($this->aps[$ap_name]['x']);
        $device['y'] = Device::generateApproximateCoordinatesAroundAP($this->aps[$ap_name]['y']);
        $device['floor_id'] = $this->aps[$ap_name]['floor_id']; 
        $device['school_id'] = $this->aps[$ap_name]['school_id'];
      }
      
      if ($this->debug) {
        //echo 'Updated Device in DB: ' , $mac, ' (AP name:' . $device['ap_name'] . ')', PHP_EOL;
      }
      
      Device::findAndUpdate(['mac_address' => $device['mac_address']], $device, false);
      
      return TRUE;
    }
}
