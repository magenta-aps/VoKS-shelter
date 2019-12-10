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
    public static function create(array $data)
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

    public static function copyToEventLogs($id)
    {
        $history = static::where('school_id', '=', $id)->get();

        foreach ($history as $value) {
            // Convert model to array
            $item = $value->toArray();

            // Process by type
            switch ($item['type']) {
                case 'push':
                    $item['log_type'] = EventLog::PUSH_NOTIFICATION_SENT;
                    $item['data'] = json_decode($item['result'], true);
                    $item['data']['message'] = $item['message'];
                    $item['data']['source_id'] = $item['source_id'];
                    break;

                case 'sms':
                    $item['log_type'] = EventLog::SMS_SENT;
                    $item['data'] = json_decode($item['result'], true);
                    $item['data']['message'] = $item['message'];
                    break;

                case 'trigger':
                case 'play':
                case 'live':
                    $item['log_type'] = EventLog::AUDIO_PLAYED;
                    $item['data'] = json_decode($item['result'], true);
                    // Extract localized message value from message key
                    $lang = School::getSettings()->locale;
                    $message = \Lang::get($item['message'], array(), $lang);
                    $item['data']['message'] = $message;
                    break;

                case 'email':
                    $item['log_type'] = EventLOG::EMAIL_SENT;
                    $item['data'] = json_decode($item['result'], true);
                    $item['data']['message'] = $item['message'];
                    break;

                default:
                    \Log::debug("Item has no known type:" . json_encode($item));
            }

            unset($item['id']); // ensure we get a new auto-incremented id
            EventLog::create($item);
        }

    }
}
