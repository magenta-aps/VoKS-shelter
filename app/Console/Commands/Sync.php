<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Console\Commands;

use BComeSafe\Models\CrisisTeamMember;
use BComeSafe\Packages\Aruba\Airwave\Importer\Import;
use Illuminate\Console\Command;

class Sync extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync aruba campuses and maps';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //sync crisis team members
        CrisisTeamMember::sync();

        //sync aruba infrastructure
        (new Import())->structure();
    }
}
