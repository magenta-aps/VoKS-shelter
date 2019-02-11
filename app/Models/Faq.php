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
 * Class Faq
 *
 * @package BComeSafe\Models
 */
class Faq extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['school_id', 'question', 'answer', 'visible', 'order'];

    /**
     * @var array
     */
    protected $casts = [
        'visible' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $guarded = [];
}
