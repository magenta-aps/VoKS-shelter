<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class SmsMessage
 *
 * @package BComeSafe\Models
 */
class SmsMessage extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'initial_message', 'crisis_team_message'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $schoolId
     * @return array
     */
    public static function getSettings($schoolId)
    {
        return static::mergeDefaults($schoolId);
    }

    /**
     * @param $schoolId
     * @param array    $defaults
     * @return array
     */
    public static function mergeDefaults($schoolId, array $defaults = [])
    {
        $defaults = SmsMessageDefault::getDefaults();

        if (!empty($defaults)) {
            $defaults = $defaults->toArray();
        } else {
            $defaults = [];
        }
        return parent::mergeDefaults($schoolId, $defaults);
    }
}
