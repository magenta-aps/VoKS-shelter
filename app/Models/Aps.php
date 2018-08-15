<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class Floor
 *
 * @package BComeSafe\Models
 */
class Aps extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'school_id',
        'floor_id',
        'ap_ale_id',
        'ap_name',
        'mac_address',
        'x',
        'y'
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\floor
     */
    public function floor()
    {
        return $this->belongsTo('BComeSafe\Models\Floor', 'floor_id', 'id');
    }

    /**
     * @param $structure
     * @return array|static
     */
    public static function import($structure)
    {
        return static::findAndUpdate(['ap_ale_id' => $structure['ap_ale_id']], $structure, false);
    }
}
