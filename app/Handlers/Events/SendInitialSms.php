<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Handlers\Events;

use BComeSafe\Events\AlarmWasTriggered;
use BComeSafe\Models\CrisisTeamMember;
use BComeSafe\Models\History;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;

class SendInitialSms
{
    /**
     * Handle the event.
     *
     * @param AlarmWasTriggered $event
     *
     * @return void
     */
    public function handle(AlarmWasTriggered $event)
    {
        try {
            // Get integration
            $defaults = SchoolDefault::getDefaults();
            $integration = \Component::get('Sms')
                ->getIntegration($defaults->sms_provider);

            // Get school settings
            $schoolId = $event->schoolId;
            $school = School::getSettings($schoolId);

            // Get crisis team members and their count
            $members = CrisisTeamMember::where('school_id', '=', $schoolId)->get();
            $memberCount = count($members);

            // Crisis message
            $message = $school->initial_message;

            // Default history message
            $history = [
                'school_id' => $schoolId,
                'type' => 'sms',
                'message' => $message,
                'result' => [
                    'count' => 0,
                    'total' => $memberCount
                ]
            ];

            // Send out text messages
            if (0 < $memberCount) {
                foreach ($members as $member) {
                    $result = $integration->sendMessage($member->phone, $message);
                    if (true === $result) {
                        $history['result']['count']++;
                    }
                }
            }

            // History message
            History::create($history);

        } catch (\Exception $e) {
        }
    }
}
