<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class SchoolDefault
 *
 * @package BComeSafe\Models
 */
class SchoolDefault extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'timezone',
        'locale',
        'ordering',
        'sms_provider',
        'phone_system_provider',
        'user_data_source',
        'client_data_source'
    ];

    /**
     * @var array
     */
    protected $guarded = ['_token'];

    /**
     * @return static
     */
    public static function getDefaults()
    {
        $defaults = static::first();
        if (!$defaults) {
            $defaults = static::firstOrNew(
                [
                'ordering' => config('sorting.default'),
                'timezone' => 'Europe/Vilnius',
                'locale' => 'en',
                'sms_provider' => 'Ucp',
                'phone_system_provider' => 'Ucp'
                ]
            );
            $defaults->setAttribute('id', 0);
        }

        return $defaults;
    }
}
