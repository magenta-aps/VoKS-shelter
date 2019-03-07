<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Handlers\Events;

use BComeSafe\Events\AskedToCallPolice;
use BComeSafe\Models\CrisisTeamMember;
use BComeSafe\Models\History;
use BComeSafe\Models\School;

use Mail;

class SendSecondaryEmail
{
    /**
     * Handle the event.
     *
     * @param AskedToCallPolice $event
     *
     * @return void
     */
    public function handle(AskedToCallPolice $event)
    {
        try {
            if (!config('mail.enabled')) return;
            
            $mail_from = config('mail.from.address');
            if (empty($mail_from)) return;
            
            $mail_from_name = config('mail.from.name');
            $mail_subject = trans('mail.alarm.secondary.subject');

            // Get school settings
            $schoolId = $event->schoolId;
            $school = School::getSettings($schoolId);
            if (empty($school)) return;

            // Get crisis team members and their count
            $members = CrisisTeamMember::where('school_id', '=', $schoolId)->get();
            if (empty($members)) return;
            $memberCount = count($members);

            // Crisis message
            $message = $school->crisis_team_message;

            // Default history message
            $history = [
                'school_id' => $schoolId,
                'type' => 'email',
                'message' => $message,
                'result' => [
                    'count' => 0,
                    'total' => $memberCount
                ]
            ];

            // Send out email messages
            if (!empty($memberCount)) {
              foreach ($members as $member) {
                $result = Mail::raw($message, function($message) use ($member) {
                  $message
                    ->from($mail_from, $mail_from_name)
                    ->to($member->email)
                    ->subject($mail_subject);
                });
                if ($result) {
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
