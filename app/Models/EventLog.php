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
    const ALARM_TRIGGERED = "alarm_triggered";
    const ASKED_TO_CALL_POLICE = "asked_to_call_police";
    const PUSH_NOTIFICATION_SENT = "push_notification_sent";
    const SMS_SENT = "sms_sent";
    const SHELTER_RESET = "shelter_reset";
    const AUDIO_PLAYED = "audio_played";

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

    public static function create(array $data)
    {
        // Set Shelter identifier
        if (!array_key_exists('school_id', $data)) {
            $data['school_id'] = \Shelter::getID();
        }

        // JSON encode result array
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
        \Log::debug($data);
        return parent::create($data);
    }
}