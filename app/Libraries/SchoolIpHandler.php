<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Libraries;

use BComeSafe\Models\School;

/**
 * Class SchoolIpHandler
 *
 * @package  BComeSafe\Libraries
 */
class SchoolIpHandler
{

    /**
     * @param $ipAddress
     * @param $schoolId
     * @return array
     */
    public function check($ipAddress, $schoolId)
    {
        $school = School::find($schoolId);

        if ($school) {
            if ($school->ip_address == $ipAddress) {
                return ['success' => true];
            }
        }

        return ['success' => false];
    }

    /**
     * @return static
     */
    public function getMappedList()
    {
        $schools = School::all(['id', 'ip_address'])->keyBy('id');
        return $schools;
    }

    /**
     * @param $ipAddress
     * @param $schoolId
     * @return string
     */
    public function validateUniqueness($ipAddress, $schoolId)
    {
        if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
            return \Lang::get('validation.valid_ip');
        } else {
            $model = School::where('ip_address', '=', $ipAddress)->first();
            if (!is_null($model) && $model->id != $schoolId) {
                return \Lang::get('validation.ip_in_use');
            } elseif (!is_null($model) && $model->id == $schoolId) {
                return 'true';
            }
        }
    }
}