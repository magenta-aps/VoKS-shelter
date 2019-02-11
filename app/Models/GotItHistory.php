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
 * Class GotItHistory
 *
 * @package BComeSafe\Models
 */
class GotItHistory extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'got_it_history';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'notification_id', 'device_id'];

    /**
     * @var array
     */
    protected $guarded = [];
}
