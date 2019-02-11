<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Providers;

use BComeSafe\Models\CrisisTeamMember;
use BComeSafe\Models\Faq;
use BComeSafe\Models\FaqDefault;
use BComeSafe\Models\PushNotificationMessage;
use BComeSafe\Models\PushNotificationMessageDefault;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolStatus;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'BComeSafe\Events\AlarmWasTriggered' => [
            'BComeSafe\Handlers\Events\WakeUp',
            'BComeSafe\Handlers\Events\SendInitialSms',
            'BComeSafe\Handlers\Events\PlayInitialSound'
        ],
        'BComeSafe\Events\AskedToCallPolice' => [
            'BComeSafe\Handlers\Events\SendSecondarySms',
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        School::created(
            function ($school) {
        
                SchoolStatus::create(
                    [
                    'school_id' => $school->id,
                    'last_active' => null
                    ]
                );

                /**
             * Import default FAQs
             */
                $defaults = FaqDefault::all();
                foreach ($defaults as $item) {
                    $item = $item->toArray();
                    $item['school_id'] = $school->id;

                    Faq::create($item);
                }

                /**
             * Import default push notifications
             */
                $defaults = PushNotificationMessageDefault::all();
                foreach ($defaults as $item) {
                    $item = $item->toArray();
                    $item['school_id'] = $school->id;

                    PushNotificationMessage::create($item);
                }

                /**
             * Import crisis team members
             */
                CrisisTeamMember::sync($school->id);

            }
        );

        School::deleted(
            function ($school) {
                Faq::where('school_id', '=', $school->id)->delete();
                PushNotificationMessage::where('school_id', '=', $school->id)->delete();
            }
        );
    }
}
