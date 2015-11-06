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
class Floor extends BaseModel
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
        'building_id',
        'floor_image_id',
        'floor_ale_id',
        'floor_name',
        'floor_number',
        'hash_id'
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building()
    {
        return $this->belongsTo('BComeSafe\Models\Building', 'building_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->hasOne('BComeSafe\Models\FloorImage', 'floor_id', 'id');
    }

    /**
     * @param $structure
     * @return array|static
     */
    public static function import($structure)
    {
        return static::findAndUpdate(['floor_ale_id' => $structure['floor_ale_id']], $structure, false);
    }
}
