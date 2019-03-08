<?php
/**
 * Created by IntelliJ IDEA.
 * User: emt
 * Date: 3/1/19
 * Time: 9:46 AM
 */

namespace BComeSafe\Models;


class EventLog extends BaseModel
{
    protected $fillable = [
        'log_type',
        'school_id',
        'device_type',
        'device_id',
        'fullname',
        'mac_address',
        'floor_id',
        'x',
        'y',
        'data'
    ];

    public $timestamps = true;

    protected $guarded = [];
}