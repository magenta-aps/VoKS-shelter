<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'BComeSafe\Console\Commands\Inspire',
        'BComeSafe\Console\Commands\ResetShelters',
        'BComeSafe\Console\Commands\Sync',
        'BComeSafe\Console\Commands\SyncMacs',
        'BComeSafe\Console\Commands\SyncCleanClients',
        'BComeSafe\Console\Commands\ArubaSyncActive',
        'BComeSafe\Console\Commands\ArubaSyncDetails',
        'BComeSafe\Console\Commands\ArubaSyncCoordinates',
		//'BComeSafe\Console\Commands\ArubaSyncClearpass', //Not ready
        'BComeSafe\Console\Commands\CiscoSyncClients',
        'BComeSafe\Console\Commands\CiscoSyncDetails',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync')->hourly();
        $schedule->command('sync')->daily();
    }
}
