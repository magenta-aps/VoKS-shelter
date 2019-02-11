<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FailedJob
 *
 * @package BComeSafe\Models
 */
class FailedJob extends Model
{

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = ['connection', 'queue', 'payload', 'failed_at'];

    /**
     * @var array
     */
    protected $guarded = [];
}
