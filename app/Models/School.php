<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

/**
 * Class School
 *
 * @package BComeSafe\Models
 */
class School extends BaseModel
{

    /**
     * @type bool
     */
    public $timestamps = false;

    /**
     * @type array
     */
    protected $fillable = [
        'name',
        'locale',
        'mac_address',
        'ip_address',
        'campus_id',
        'timezone',
        'ordering',
        'ad_id',
        'phone_system_id',
        'phone_system_voice_id',
        'phone_system_group_id',
        'phone_system_number',
    ];

    /**
     * @type array
     */
    protected $guarded = [];

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function getShelterByPeerId($id)
    {
        $shelter = School::first();

        return $shelter;
    }

    /**
     * Relationship to Campus model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function campus()
    {
        return $this->hasOne('BComeSafe\Models\Campus', 'school_id', 'id');
    }

    /**
     * @param $id
     *
     * @return null
     */
    public static function getShelterByDeviceId($id)
    {
        $device = Device::where('device_id', '=', $id)->first();

        if ($device) {
            $shelter = $device->shelter;
            if ($shelter) {
                return $shelter;
            }
        }

        return null;
    }

    /**
     * @param $hash
     *
     * @return \Illuminate\Support\Collection|null|static
     */
    public static function getByCampusHash($hash)
    {
        if (!empty($hash)) {
            $campus = Campus::where('hash_id', '=', $hash)->with('school')->first();
            if (!empty($campus->school)) {
                $school = $campus->school;
            }
            $school = School::find(1);
        } else {
            // hack!
            $school = School::find(1);
        }

        return $school;
    }

    /**
     * @param null $schoolId
     *
     * @return object
     */
    public static function getSettings($schoolId = null)
    {
        if (empty($schoolId)) {
            $schoolId = \Shelter::getID();
        }

        $settings = static::find($schoolId);
        $settings = array_merge_filled(
            SchoolDefault::getDefaults()->toArray(),
            !empty($settings)
            ? $settings->toArray()
            : []
        );

        //set up sms message texts
        $messages = SmsMessage::getSettings($settings['id']);
        $settings['initial_message'] = $messages['initial_message'];
        $settings['crisis_team_message'] = $messages['crisis_team_message'];

        return (object)$settings;
    }

    /**
     * @param $shelterId
     *
     * @return array
     */
    public static function getMaps($shelterId)
    {
        $campuses = School::with('campus.buildings.floors.image')->find($shelterId)->toArray();
        $campuses = $campuses['campus'];

        $buildings = [];
        //if there are no buildings stop execution
        if (!isset($campuses['buildings'])) {
            return $buildings;
        }

        foreach ($campuses['buildings'] as $building) {
            //if there are no floors stop execution
            if (empty($building['floors'])) {
                continue;
            }

            $floors = [];
            foreach ($building['floors'] as $floor) {
                //if there is no floor image stop execution
                if (!isset($floor['image'])) {
                    continue;
                }

                $floors[] = [
                    'name'     => $floor['floor_name'],
                    'floor_id' => $floor['id'],
                    'image'    => [
                        'path'       => '/uploads/maps/' . $floor['image']['file_name'],
                        'floor_id'   => $floor['id'],
                        'dimensions' => [
                            'width'  => $floor['image']['pixel_width'],
                            'height' => $floor['image']['pixel_height']
                        ]
                    ]
                ];
            }

            $buildings[] = [
                'name'   => $building['building_name'],
                'id'     => $building['id'],
                'floors' => $floors
            ];
        }

        return $buildings;
    }
    
    /**
     * @param
     *
     * @return int
     */
    public static function getDefaultSchoolID() {
      $school_id = 0;
      
      $school = School::where('display', '=', '1')->where('public', '=', '1')->where('ip_address', '=', \Request::ip())->get()->first();
      if ($school) {
        $school = $school->toArray();
      }
      
      if (!empty($school['id'])) {
        $school_id = $school['id'];
      } 
      elseif (env('SCHOOL_ID')) {
        $school_id = env('SCHOOL_ID');
      }
      
      return $school_id;
    }
}
