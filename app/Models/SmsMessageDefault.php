<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class SmsMessageDefault
 *
 * @package BComeSafe\Models
 */
class SmsMessageDefault extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = ['initial_message', 'crisis_team_message'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return static
     */
    public static function getDefaults()
    {
        $defaults = static::first();
        if (!$defaults) {
            $defaults = static::firstOrNew(
                [
                'initial_message' => '',
                'crisis_team_message' => ''
                ]
            );
        }

        return $defaults;
    }
}
