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

class ArubaSyncCoordinates extends Command
{

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
        $floors = Floor::with('image')->get()->toArray();
        $floors = array_map_by_key($floors, 'floor_hash_id');

        $start = microtime(true);

        $locations = Location::getAllCoordinates();
        $count = count($locations);

        $clients = [];

        for ($i = 0; $i < $count; $i++) {
            $location = $locations[$i];

            if (!isset($floors[$locations[$i]['floor_id']])) {
                continue;
            }

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

            $clients[] = $client;
        }

        Device::updateClientCoordinates($clients);

        $end = (double)number_format(microtime(true) - $start, 4);

        echo $end, PHP_EOL;
    }
}
