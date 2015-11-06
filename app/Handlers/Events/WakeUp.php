<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Handlers\Events;

use BComeSafe\Events\AlarmWasTriggered;
use BComeSafe\Libraries\WakeOnLan;
use BComeSafe\Models\School;

class WakeUp
{

    protected $school;

    /**
     * Handle the event.
     *
     * @param  AlarmWasTriggered $event
     * @return void
     */
    public function handle(AlarmWasTriggered $event)
    {
        $this->school = School::getSettings($event->schoolId);

        try {
            WakeOnLan::sendMagicPacket($this->school->ip_address, $this->school->mac_address, config('alarm.wol_port'));
        } catch (\Exception $e) {
        }
    }
}
