<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\Device;
use BComeSafe\Packages\Aruba\Ale\AleLocation;
use GuzzleHttp\Promise;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

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
      * Get the console command arguments.
      *
      * @return array
      */
    protected function getArguments()
    {
      return [
        ['ale', InputArgument::OPTIONAL, 'Which ALE server'],
      ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //ALE server
        $serverNumber = NULL;
        $ale = $this->argument('ale');
        if(!empty($ale) && in_array($ale, array(1, 2, 3))) {
          $serverNumber = $ale;
        }

        $full_time_start = $time_start = microtime(true);
        $stations = AleLocation::getStations(FALSE, $serverNumber);
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Time for geting active devices from ALE: ", $execution_time, PHP_EOL;
        echo "ALE server: " , $serverNumber, PHP_EOL;
        echo "Count (only active): ", count($stations), PHP_EOL;
        //
        $time_start = microtime(true);
        Device::updateActiveClients($stations);
        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;
        echo "Execution time for updating DB (active): ", $execution_time, PHP_EOL;

        $full_time_end = microtime(true);
        $execution_time = $full_time_end - $full_time_start;
        echo "Execution time for ALL: ", $execution_time, PHP_EOL;

    }
}
