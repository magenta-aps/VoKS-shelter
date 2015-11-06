<?php
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */
namespace BComeSafe\Models;

/**
 * Class HelpFileDefault
 *
 * @package BComeSafe\Models
 */
class HelpFileDefault extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'description',
        'name',
        'file_path',
        'file_url',
        'police_name',
        'police_file_path',
        'police_file_url'
    ];

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
                'file_url'    => '',
                'file_path'   => '',
                'description' => ''
                ]
            );
        }

        return $defaults;
    }
}
