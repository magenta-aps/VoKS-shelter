<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class SchoolStatus
 *
 * @package BComeSafe\Models
 */
class SchoolStatus extends BaseModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
      'school_id', 
      'status_alarm', 
      'status_police', 
      'last_active',
      'triggered_at'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status_alarm' => 'integer',
        'status_police' => 'integer'
    ];

    /**
     * List of columns that get converted to Carbon objects
     *
     * @return array
     */
    public function getDates()
    {
        return ['last_active', 'triggered_at'];
    }

    /**
     * @return array
     */
    public static function getActive()
    {
        $statuses = static::all();

        $schools = [];

        for ($i = 0; $i < count($statuses); $i++) {
            if (time() - (config('alarm.activity_timeout') * 60) <= strtotime($statuses[$i]['last_active'])) {
                $schools[] = $statuses[$i]['school_id'];
            }
        }

        return $schools;
    }

    /**
     * Update alarm status.
     *
     * @param integer $schoolId Shelter/School identifier
     * @param integer $status   Alarm status 1/0 (ON/OFF)
     */
    public static function statusAlarm($schoolId = null, $status)
    {
        if (null === $schoolId) {
            $schoolId = \Shelter::getID();
        }
        
        $data = [
          'school_id' => $schoolId,
          'last_active' => date('Y-m-d H:i:s'),
          'status_alarm' => $status
        ];
        
        if ($status == 1) {
          $data['triggered_at'] = $data['last_active'];
        }
        
        static::findAndUpdate(
            ['school_id' => $schoolId],
            $data
        );
    }

    /**
     * Update police status.
     *
     * @param integer $schoolId Shelter/School identifier
     * @param integer $status   Police status 1/0 (Called/Not called)
     */
    public static function statusPolice($schoolId = null, $status)
    {
        if (null === $schoolId) {
            $schoolId = \Shelter::getID();
        }

        static::findAndUpdate(
            ['school_id' => $schoolId],
            [
            'school_id' => $schoolId,
            'last_active' => date('Y-m-d H:i:s'),
            'status_police' => $status
            ]
        );
    }

    /**
     * @param null $schoolId
     */
    public static function updateActivity($schoolId = null)
    {
        if (null === $schoolId) {
            $schoolId = \Shelter::getID();
        }

        static::findAndUpdate(['school_id' => $schoolId], ['school_id' => $schoolId, 'last_active' => date('Y-m-d H:i:s')]);
    }

    /**
     * @param null $schoolId
     */
    public static function unsetActivity($schoolId = null)
    {
        if (null === $schoolId) {
            $schoolId = \Shelter::getID();
        }

        static::findAndUpdate(['school_id' => $schoolId], ['school_id' => $schoolId, 'last_active' => '0000-00-00 00:00:00']);
    }
}
