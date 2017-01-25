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
class ArubaSyncActive extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aruba:sync:active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update active Aruba clients';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $full_time_start = $time_start = microtime(true);
        $macAddresses = Location::getStations();
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Time for geting active devices from ALE: ", $execution_time, PHP_EOL;
        echo "Count (active): ", count($macAddresses), PHP_EOL;
        //
        $time_start = microtime(true);
        Device::updateActiveClients($macAddresses);
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Execution time for updating DB (active): ", $execution_time, PHP_EOL;

        $full_time_end = microtime(true);
        $execution_time = $full_time_end - $full_time_start;
        echo "Execution time for ALL: ", $execution_time, PHP_EOL;

    }
}
