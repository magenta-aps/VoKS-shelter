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
use BComeSafe\Packages\Aruba\Ale\Coordinates;
use BComeSafe\Packages\Aruba\Ale\Location;
use GuzzleHttp\Promise;
use Illuminate\Console\Command;

/**
 * Class ArubaSyncCoordinates
 *
 * @package  BComeSafe\Console\Commands
 */
class ArubaSyncCoordinates extends Command
{
    const RUNS_PER_MINUTE = 6;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aruba:sync:coordinates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update client coordinates';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $full_time_start = microtime(true);
        $floors = Floor::with('image')->get()->toArray();
        $floors = array_map_by_key($floors, 'floor_hash_id');

        for ($i = 0; $i < self::RUNS_PER_MINUTE; $i++) {
            $this->runUpdate($floors);

            if ($i !== self::RUNS_PER_MINUTE - 1) {
                sleep(round(60 / self::RUNS_PER_MINUTE));
            }
        }

        $full_time_end = microtime(true);
        $execution_time = $full_time_end - $full_time_start;
        echo "Execution time for ALL: ", $execution_time, PHP_EOL;
    }

    /**
     * Pull locations and update device records
     *
     * @param $floors
     */
    public function runUpdate($floors)
    {
        $time_start = microtime(true);
        $locations = Location::getAllCoordinates();
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Time for geting locations from ALE: ", $execution_time, PHP_EOL;
        $count = count($locations);
        echo "Count (locations): ", $count, PHP_EOL;

        $clients = [];

        for ($i = 0; $i < $count; $i++) {
            if (!isset($floors[$locations[$i]['floor_id']])) {
                continue;
            }

            $clients[] = $this->mapClientModel($floors, $locations[$i]);
        }

        $time_start = microtime(true);
        Device::updateClientCoordinates($clients);
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Execution time for updating DB (location) : ", $execution_time, PHP_EOL;
        echo "Count (Clients): ", count($clients), PHP_EOL;
        echo "*****************", PHP_EOL;
    }

    /**
     * @param $floors
     * @param $location
     * @return array
     */
    protected function mapClientModel($floors, $location)
    {
        $floor = $floors[$location['floor_id']];

        $client['x'] = $location['x'];
        $client['y'] = $location['y'];
        $client['floor_id'] = $floors[$location['floor_id']]['id'];
        $client['school_id'] = $floors[$location['floor_id']]['school_id'];
        $client['mac_address'] = $location['mac_address'];

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
