<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class History
 *
 * @package BComeSafe\Models
 */
class History extends BaseModel
{

    protected $table = 'history';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'source_id', 'type', 'message', 'result'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param array $data
     * @return static
     */
    public static function create(array $data = [])
    {
        // Set Shelter identifier
        if (!array_key_exists('school_id', $data)) {
            $data['school_id'] = \Shelter::getID();
        }

        // JSON encode result array
        if (is_array($data['result'])) {
            $data['result'] = json_encode($data['result']);
        }

        return parent::create($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('BComeSafe\Models\Device', 'device_id', 'device_id');
    }

    /**
     * @param $device_id
     * @param $notification_id
     */
    public static function gotIt($device_id, $notification_id)
    {
        $history = GotItHistory::firstOrNew([
            'device_id'       => $device_id,
            'school_id'       => \Shelter::getID(),
            'notification_id' => $notification_id
        ]);

        // create a new record if one doesn't exist
        if (!$history->exists) {
            $push = static::where('source_id', '=', $notification_id)->first();
            if ($push) {
                $result = json_decode($push->result, true);
                $result['read']++;
                $push->result = json_encode($result);
                $push->save();
                $history->save();
            }
        }
    }

    /**
     * @param $notification
     * @param $count
     */
    public static function pushNotification($notification, $count)
    {
        History::create([
            'type'      => 'push',
            'message'   => $notification->message,
            'source_id' => $notification->id,
            'result'    => json_encode(['total' => $count, 'read' => 0])
        ]);
    }
}
