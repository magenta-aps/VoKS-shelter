<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Events;

use Illuminate\Queue\SerializesModels;

class AlarmWasTriggeredByShelter extends AlarmWasTriggered
{
    use SerializesModels;

    /**
     * Create a new event instance.
     * @param $schoolId
     */
    public function __construct($schoolId)
    {
        parent::__construct($schoolId, null);
    }
}
