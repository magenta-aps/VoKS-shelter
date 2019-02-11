<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class FloorImage
 *
 * @package BComeSafe\Models
 */
class FloorImage extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'floor_id',
        'name',
        'file_name',
        'max_zoom_level',
        'path',
        'pixel_width',
        'pixel_height',
        'real_width',
        'real_height'
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $structure
     * @return array|static
     */
    public static function import($structure)
    {
        return static::findAndUpdate(['floor_id' => $structure['floor_id']], $structure, false);
    }
}
