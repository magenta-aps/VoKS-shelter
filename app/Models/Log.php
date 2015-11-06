<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class Log
 *
 * @package BComeSafe\Models
 */
class Log extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = ['device_id', 'device_type', 'data'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $data
     * @param string $where
     */
    public static function debug($data, $where = 'debug')
    {
        static::create(
            [
            'device_id' => 'debug',
            'device_type' => $where,
            'data' => json_encode($data)
            ]
        );
    }
}
