<?php
/**
 * Created by IntelliJ IDEA.
 * User: emt
 * Date: 3/1/19
 * Time: 9:46 AM
 */

namespace BComeSafe\Models;


class EventReport extends BaseModel
{
    protected $fillable = [
        'school_id',
        'device_type',
        'device_id',
        'fullname',
        'created_at',
        'data',
        'push_notifications',
        'video_chats',
        'duration',
    ];

    public $timestamps = false;

    protected $guarded = [];
}
