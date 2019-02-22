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
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class CiscoSyncClients
 *
 * @package  BComeSafe\Console\Commands
 */
class CiscoSyncClients extends Command {

    const MAX_ALLOWED_RUNS = 999;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cisco:sync:clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all client from Cisco';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        if (!config('cisco.enabled')) return;

        //*** Debug ***
        $debug = config('app.debug');
        $clients_debug = config('cisco.clientsDebug');
        $clients_debug_arr = array();
        if ($debug && !empty($clients_debug)) {
          echo "Debuging clients: ", $clients_debug, PHP_EOL;
          $clients_debug_arr = explode(',', $clients_debug);
        }

        //*** Debug ***
        if ($debug) $full_time_start = microtime(true);

        //Floors
        $floors = Floor::with('image')->get()->toArray();
        $floors = array_map_by_key($floors, 'floor_hash_id');

        //Get Clients
        $clients = array();
        for ($page = 0; $page < self::MAX_ALLOWED_RUNS; $page++) {
          //*** Debug ***
          if ($debug) echo "Page: ", $page, PHP_EOL;
          if ($debug) $time_start = microtime(true);

          //Get Clients from Cisco CMX
          $coordinates = CmxLocation::getAllCoordinates($page);

          //*** Debug ***
          if ($debug) $time_end = microtime(true);
          if ($debug) {
            $count = count($coordinates);
            echo "Count (Cisco clients on page " . $page . "): ", $count, PHP_EOL;
          }
          if ($debug) $execution_time = $time_end - $time_start;
          if ($debug) echo "Time for geting clients from Cisco: ", $execution_time, PHP_EOL;

          //Merge clients
          if (!empty($coordinates)) {
            $clients = array_merge($clients, $coordinates);
          }
          else {
            break;
          }
        }

        //*** Debug ***
        if ($debug) {
          echo "*****************", PHP_EOL;
          $count = count($clients);
          echo "Count (Cisco clients total): ", $count, PHP_EOL;
        }

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

          //*** Debug ***
          if ($debug) {
            $count = count($clients);
            echo "*****************", PHP_EOL;
            echo "Count (clients) after Floor filtering: ", $count, PHP_EOL;
          }

          //*** Debug ***
          if ($debug) {
            echo "*****************", PHP_EOL;
            echo "Clients: ", PHP_EOL;
            $i = 1;
            foreach ($clients as $k => $client) {
              if ($debug && !empty($clients_debug_arr)) {
                if (in_array($client['mac_address'], $clients_debug_arr)) {
                  print_r($client);
                  echo PHP_EOL;
                }
              }
              else {
                $k . ' | ' . print_r($client);
                echo PHP_EOL;
                if ($i >= 5) break;
                $i++;
              }
            }
            echo "...", PHP_EOL;
          }

          //*** Debug ***
          if ($debug) $time_start = microtime(true);

          //Update Clients in DB
          Device::updateClients($clients);
        }
        else {
          //*** Debug ***
          if ($debug) {
            echo "No Clients found!", PHP_EOL;
          }
        }

        //*** Debug ***
        if ($debug) echo "*****************", PHP_EOL;
        if ($debug) $time_end = microtime(true);
        if ($debug) $execution_time = $time_end - $time_start;
        if ($debug) echo "Execution time for updating DB: ", $execution_time, PHP_EOL;
        if ($debug) echo "*****************", PHP_EOL;

        if ($debug) $full_time_end = microtime(true);
        if ($debug) $execution_time = $full_time_end - $full_time_start;
        if ($debug) echo "Execution time TOTAL: ", $execution_time, PHP_EOL;
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
        $client['fullname'] = $client_cisco['username'];

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
