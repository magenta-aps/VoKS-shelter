<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\GotItHistory;
use BComeSafe\Models\History;
use BComeSafe\Models\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Command;

class SyncCleanClients extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sync:clean:clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean clients from DB';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        //
        GotItHistory::truncate();
        //
        History::truncate();
        //
        Device::truncate();
        //
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
