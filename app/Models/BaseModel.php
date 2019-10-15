<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

use \Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @package BComeSafe\Models
 */
class BaseModel extends Model
{

    /**
     * @param $by
     * @param $update
     * @param bool|true $array
     * @return array|static
     */
    public static function findAndUpdate($by, $update, $array = true)
    {
        $model = static::firstOrNew($by);

        foreach ($update as $key => $value) {
            $model->setAttribute($key, $value);
        }

        if (!$model->exists) {
            $model->save();
        } else {
            $model->update();
        }

        if ($array) {
            return $model->toArray();
        }

        return $model;
    }

    /**
     * @param $id
     */
    public static function truncateForShelter($id)
    {
        if (config('eventlog.active')) {
            static::copyToEventLogs($id);
        }
        static::where('school_id', '=', $id)->delete();
    }

    public static function copyToEventLogs($id)
    {
        return $id; // empty function for overloading where we want to save logs
    }

    /**
     * @param array $where
     * @param array $attributes
     * @param array $ignore
     * @return array|BaseModel
     */
    public static function updateOrCreateWithIgnore(Array $where, Array $attributes, Array $ignore)
    {
        foreach ($ignore as $key) {
            unset($attributes[$key]);
        }

        return self::findAndUpdate($where, $attributes, false);
    }

    /**
     * @param $shelterId
     * @param array     $defaults
     * @return array
     */
    public static function mergeDefaults($shelterId, array $defaults = [])
    {
        $settings = static::where('school_id', '=', $shelterId)->get()->first();

        if ($settings) {
            $settings = $settings->toArray();
        } else {
            $settings = [];
        }

        return array_merge_filled($defaults, $settings);
    }
}
