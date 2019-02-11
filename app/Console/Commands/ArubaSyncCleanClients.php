<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\Device;
use Illuminate\Console\Command;

class ArubaSyncCleanClients extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'aruba:sync:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Aruba clients from DB';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Delete all clients
        Device::deleteAllClients();
    }
}
