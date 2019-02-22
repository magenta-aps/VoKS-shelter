<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

use BComeSafe\Libraries\ActiveDirectory;

/**
 * Class CrisisTeamMember
 *
 * @package BComeSafe\Models
 */
class CrisisTeamMember extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'name', 'email', 'phone'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param null $schoolId
     * @return array
     */
    public static function sync($schoolId = null)
    {
        // clears all records if no schoolId is passed
        if ($schoolId === null) {
            static::truncate();
            $schools = School::all(['id', 'ad_id']);
        } else {
            static::where('school_id', '=', $schoolId)->delete();
            $schools = School::where('id', '=', $schoolId)->get();
        }

        if (empty($schools)) {
          return array();
        }
        
        foreach ($schools as $school) {
            $members = ActiveDirectory::getGroupMembers($school->ad_id);

            if (!empty($members)) {
                $attributes = config('ad.field-map');
                /**
                 * @var $member \Adldap\Models\User
                 */
                foreach ($members as $member) {
                    foreach ($attributes as $key => $attribute) {
                        $model[$key] = (string)$member->getAttribute($attribute, 0);
                    }

                    $model['school_id'] = $school->id;
                    static::create($model);
                }
            }
        }

        return $members;
    }
}
