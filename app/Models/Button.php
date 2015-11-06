<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class Button
 *
 * @package BComeSafe\Models
 */
class Button extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['floor_id', 'x', 'y', 'button_name', 'mac_address', 'ip_address', 'button_number'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor()
    {
        return $this->belongsTo('BComeSafe\Models\Floor', 'floor_id', 'id');
    }

    /**
     * @param $id
     * @return array
     */
    public static function getBySchoolId($id)
    {
        return \DB::select(
            "
            SELECT b.id, b.x, b.y, b.button_name, b.button_number, b.ip_address,
            b.mac_address, f.floor_name, f.id as floor_id, bi.building_name, c.campus_name
            FROM buttons AS b
            INNER JOIN floors AS f ON b.floor_id = f.id
            INNER JOIN buildings AS bi ON f.building_id = bi.id
            INNER JOIN campuses AS c ON bi.campus_id = c.id
            WHERE c.school_id = :school_id
            GROUP BY b.id
        ",
            ['school_id' => $id]
        );
    }
}
