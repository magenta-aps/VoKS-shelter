<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Handlers\Events;

use BComeSafe\Events\AlarmWasTriggered;
use BComeSafe\Models\History;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;

class PlayInitialSound
{
    /**
     * Handle the event.
     *
     * @param  AlarmWasTriggered $event
     * @return void
     */
    public function handle(AlarmWasTriggered $event)
    {
        try {
            // Get integration
            $defaults = SchoolDefault::getDefaults();
            $integration = \Component::get('PhoneSystem')
                ->getIntegration($defaults->phone_system_provider);

            // Get school settings
            $schoolId = $event->schoolId;
            $school = School::getSettings($schoolId);

            // Get node identifier
            $nodeId = $school->phone_system_id;

            // Validate voice
            $voice = null;
            $voices = $integration->getVoices($nodeId);
            $voiceId = $school->phone_system_voice_id;
            if (array_key_exists($voiceId, $voices)) {
                $voice = $voices[$voiceId];
            } else {
                $voiceId = 0;
            }

            // Validate group
            $group = null;
            $groups = $integration->getGroups($nodeId);
            $groupId = $school->phone_system_group_id;
            if (array_key_exists($groupId, $groups)) {
                $group = $groups[$groupId];
            } else {
                $groupId = 0;
            }

            // Validate interrupt
            $interrupt = null;
            $interrupts = $voices;
            $interruptId = $school->phone_system_interrupt_id;
            if (array_key_exists($interruptId, $interrupts)) {
                $interrupt = $interrupts[$interruptId];
            } else {
                $interruptId = 0;
            }

            // Default history message
            $history = [
                'school_id' => $schoolId,
                'type' => 'trigger',
                'message' => 'shelter/history.audio.trigger.message',
                'result' => [
                    'voice' => $voice,
                    'group' => $group,
                    'interrupt' => $interrupt,
                    'status' => false
                ]
            ];

            // Update Shelter settings if any of the ids is 0
            if (0 === $voiceId || 0 === $groupId || 0 === $interruptId) {
                School::findAndUpdate(
                    ['id' => $schoolId],
                    [
                    'phone_system_voice_id' => $voiceId,
                    'phone_system_group_id' => $groupId,
                    'phone_system_interrupt_id' => $interruptId
                    ]
                );
            }

            // Play the voice message
            if (0 !== $voiceId && 0 !== $groupId) {
                $result = $integration->play($nodeId, $voiceId, $groupId, $interruptId, 0, 0);
                if (true === $result) {
                    $history['result']['status'] = true;
                }
            }

            // History message
            History::create($history);

        } catch (\Exception $e) {
            \Log::debug($e);
        }
    }
}
