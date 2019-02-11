<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class HelpFile
 *
 * @package BComeSafe\Models
 */
class HelpFile extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = [
        'school_id',
        'type',
        'name',
        'file_path',
        'file_url',
        'description',
        'police_name',
        'police_file_path',
        'police_file_url'
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return array
     */
    public static function getFile()
    {
        $settings = static::where('school_id', '=', \Shelter::getID())->get()->first();
        if (!$settings) {
            $settings = HelpFileDefault::getDefaults();
            $settings->setAttribute('id', 'default');
            $settings = $settings->toArray();
        } else {
            $settings = array_merge(HelpFileDefault::getDefaults()->toArray(), $settings->toArray());
        }

        return [$settings];
    }
}
