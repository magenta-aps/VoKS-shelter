<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Events;

use Illuminate\Queue\SerializesModels;

class AlarmWasTriggered extends Event
{
    use SerializesModels;

    public $schoolId;
    public $deviceId;

    /**
     * Create a new event instance.
     */
    public function __construct($schoolId, $deviceId)
    {
        $this->schoolId = $schoolId;
        $this->deviceId = $deviceId;
    }
}
