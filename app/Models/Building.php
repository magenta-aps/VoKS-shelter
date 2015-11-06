<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class Building
 *
 * @package BComeSafe\Models
 */
class Building extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'campus_id', 'building_ale_id', 'building_name'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function floors()
    {
        return $this->hasMany('BComeSafe\Models\Floor', 'building_id', 'id')->orderBy('floor_number', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campus()
    {
        return $this->belongsTo('BComeSafe\Models\Campus', 'campus_id', 'id');
    }

    /**
     * @param $structure
     * @return array|static
     */
    public static function import($structure)
    {
        return static::findAndUpdate(['building_ale_id' => $structure['building_ale_id']], $structure, false);
    }
}
