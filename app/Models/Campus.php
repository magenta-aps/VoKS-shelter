<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class Campus
 *
 * @package BComeSafe\Models
 */
class Campus extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'campus_ale_id', 'campus_name', 'campus_hash_id'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buildings()
    {
        return $this->hasMany('BComeSafe\Models\Building', 'campus_id', 'id')->orderBy('building_name', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('BComeSafe\Models\School', 'school_id', 'id');
    }

    /**
     * @param $structure
     * @return array|static
     */
    public static function import($structure)
    {
        return static::findAndUpdate(['campus_ale_id' => $structure['campus_ale_id']], $structure, false);
    }
}
