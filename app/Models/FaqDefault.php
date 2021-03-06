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
 * Class FaqDefault
 *
 * @package BComeSafe\Models
 */
class FaqDefault extends Model
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['question', 'answer', 'order', 'visible'];

    /**
     * @var array
     */
    protected $guarded = [];
}
