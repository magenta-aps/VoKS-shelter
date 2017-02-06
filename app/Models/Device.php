<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

use BComeSafe\Packages\Aruba\Ale\Location;
use BComeSafe\Packages\Aruba\ArubaIntegrationException;
use BComeSafe\Packages\Aruba\Clearpass\User;

/**
 * Class Device
 *
 * @package BComeSafe\Models
 */
class Device extends BaseModel
{
    /**
     *
     */
    const INACTIVE = 0;
    /**
     *
     */
    const TRIGGERED = 1;
    /**
     *
     */
    const CALLED = 2;
    /**
     *
     */
    const ASKED_TO_CALL = 3;

    /**
     * @type array
     */
    protected $fillable = [
        'school_id',
        'trigger_status',
        'fullname',
        'device_type',
        'device_id',
        'push_notification_id',
        'mac_address',
        'x',
        'y',
        'active',
        'floor_id',
        'triggered_at'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'x' => 'int',
        'y' => 'int',
    ];

    /**
     * @type array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shelter()
    {
        return $this->belongsTo('BComeSafe\Models\School', 'school_id', 'id');
    }

    /**
     * @return array
     */
    public function getDates()
    {
        return ['created_at', 'updated_at', 'triggered_at'];
    }

    /**
     * @param $schoolId
     */
    public static function updateAllToInactive($schoolId)
    {
        \DB::table('devices')
            ->where('school_id', '=', $schoolId)
            ->update(['trigger_status' => self::INACTIVE]);
    }

    /**
     * @param $schoolId
     */
    public static function updateAllToNotCalled($schoolId)
    {
        \DB::table('devices')
            ->where('school_id', '=', $schoolId)
            ->where('trigger_status', '>', self::INACTIVE)
            ->update(['trigger_status' => self::TRIGGERED]);

        // Set Shelter police status to not called
        SchoolStatus::statusPolice($schoolId, 0);
    }

    /**
     * @param $mac
     *
     * @return array
     */
    public static function getByMac($mac)
    {
        $device = static::where('mac_address', '=', $mac)->first(
            [
            'mac_address', 'device_id', 'fullname', 'push_notification_id', 'device_type'
            ]
        );

        if ($device) {
            return $device->toArray();
        }

        return [];
    }

    /**
     * @param $deviceId
     */
    public function setDeviceIdAttribute($deviceId)
    {
        if (!str_contains($deviceId, '_')) {
            $deviceId = $deviceId . '_' . $this->getAttribute('device_type');
        }

        $this->attributes['device_id'] = $deviceId;
    }

    /**
     * @param $fullname
     */
    public function setFullnameAttribute($fullname)
    {
        if (!empty($fullname)) {
            $this->attributes['fullname'] = $fullname;
        } elseif (empty($this->getAttribute('fullname'))) {
            $this->attributes['fullname'] = ucfirst(strtolower($this->getAttribute('device_type')));
        }
    }

    /**
     * @return array|mixed
     * @throws ArubaIntegrationException
     */
    protected function getProfileData()
    {
        $clearpass = new User();

        if (!$this->getAttribute('mac_address')) {
            $device = $clearpass->getByIp(\Request::ip());
        } else {
            $device = $clearpass->getByMac($this->getAttribute('mac_address'));
        }

        if (!isset($device['mac_address'])) {
            throw new ArubaIntegrationException('Couldn\'t fetch the MAC Address. Are you sure you\'re connected to Aruba wifi?');
        }

        return $device;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getLocationData()
    {
        $location = Location::getCoordinates($this->getAttribute('mac_address'));
        $device = [];

        if (env('SCHOOL_ID')) {
            $floor = Floor::where('school_id', '=', env('SCHOOL_ID'))->orderBy('id', 'desc')->get()->first();

            if(empty($floor)) {
                throw new \Exception(
                    'Structure not synchronized on campus #'.env('SCHOOL_ID').'. Please wait.'
                );
            }

            $device['school_id'] = env('SCHOOL_ID');
            $device['active'] = 1;
            $device['floor_id'] = $floor->id;

            if (\Request::get('test')) {
                $device['x'] = mt_rand(100, 500);
                $device['y'] = mt_rand(100, 500);
            } else {
                $device['x'] = 0;
                $device['y'] = 0;
            }
        } else {
            if (!empty($location['campus_id'])) {
                $floor = Floor::where('floor_hash_id', '=', $location['floor_id'])->first();

                if (isset($floor->building->campus->school_id)) {
                    $device['school_id'] = $floor->building->campus->school_id;
                    $device['floor_id'] = $floor->id;
                } else {
                    static::deactivate($this->getAttribute('device_id'));

                    throw new \Exception(
                        'There are no coordinates or campuses not synchronized. Please wait.',
                        Location::COORDINATES_NOT_MAPPED
                    );
                }
            } else {
                static::deactivate($this->getAttribute('device_id'));

                throw new \Exception(
                    'We could not locate you via ALE. Please wait.',
                    Location::COORDINATES_UNAVAILABLE
                );
            }
            $device['x'] = $location['x'];
            $device['y'] = $location['y'];
            $device['active'] = 1;
        }

        return $device;
    }

    /**
     * @throws ArubaIntegrationException
     * @throws \Exception
     */
    public function updateDeviceProfile()
    {
        $this->fill($this->getProfileData());
        $this->fill($this->getLocationData());

        if (!$this->getAttribute('trigger_status')) {
            $this->setAttribute('triggered_at', date('Y-m-d H:i:s'));
        }

        $macModel = static::where('mac_address', '=', $this->getAttribute('mac_address'))->first();

        if ($macModel) {
            $attributes = $this->getAttributes();

            unset($attributes['id']);

            // just update don't insert
            $macModel->update($attributes);
        } else {
            $this->save();
        }
    }

    /**
     * @param $data
     * @param $client
     *
     * @return array
     */
    public static function setAlarmStatus($data, $client)
    {
        /**
         * For legacy purposes
         */
        switch ($data['trigger_status']) {
            case '0':
                $data['trigger_status'] = Device::TRIGGERED;
                $data['triggered_at'] = date('Y-m-d H:i:s');
                break;

            case '1':
                $data['trigger_status'] = Device::CALLED;
                break;

            case '2':
                $data['trigger_status'] = Device::ASKED_TO_CALL;
                break;
        }

        $data['already_triggered'] = false;

        $before = (int)@$client->trigger_status;
        $now = $data['trigger_status'];

        if ($before >= $now) {
            return [
                'trigger_status' => $client->trigger_status,
                'already_triggered' => true
            ];
        }

        return $data;
    }

    public static function deactivate($deviceId) {
        $device = Device::where('device_id', '=', $deviceId)->first();
        if(!empty($device)) {
            $device->update([
                'active' => 0
            ]);
        }
    }

    /**
     * @param $device
     *
     * @return array|static
     */
    public static function updateOnTrigger($device)
    {
        $client = Device::where('device_id', '=', $device['device_id'])->get()->first();
        $update = Device::setAlarmStatus($device, $client);

        $triggered = $update['already_triggered'];

        unset($update['already_triggered']);
        if (!empty($client)) {
            //only update the device model if it hasn't called the police
            $device = Device::findAndUpdate(
                [
                'device_id' => $device['device_id']
                ],
                $update
            );
        }
        $device['already_triggered'] = $triggered;

        return $device;
    }

    /**
     * @param $device
     * @return array
     */
    public static function mapDeviceCoordinates($device)
    {
        return [
            'profile' => [
                'name' => empty($device->fullname) ? $device->mac_address : $device->fullname,
                'mac_address' => $device->mac_address,
                'device' => $device->device_type,
                'device_id' => $device->device_id,
                'gcm_id' => $device->push_notification_id
            ],
            'position' => [
                'x' => $device->x,
                'y' => $device->y,
                'floor_id' => (string) $device->floor_id
            ]
        ];
    }

    /**
     * @param $macAddresses
     */
    public static function updateActiveClients($stations)
    {
        $count = count($stations);

        if (0 === $count) {
            return;
        }

        $join = '';
        $macAddresses = array();

        for ($i = 0; $i < $count; $i++) {
            $join .= "('" . $stations[$i]['mac_address'] . "', 1, CURRENT_TIMESTAMP, '" . $stations[$i]['username'] . "', '" . $stations[$i]['role'] . "'),";
            $macAddresses[] = $stations[$i]['mac_address'];
        }

        $join = rtrim($join, ',');

        \DB::insert(
            "
          INSERT INTO devices (`mac_address`, `active`, `updated_at`, `username`, `role`) VALUES $join
          ON DUPLICATE KEY UPDATE
          `mac_address` = VALUES(`mac_address`),
          `active` = VALUES(active),
          `updated_at` = CURRENT_TIMESTAMP,
          `username` = VALUES(`username`),
          `role` = VALUES(`role`);
        "
        );

        \DB::update("UPDATE devices SET active = 0 WHERE active = 1 AND mac_address NOT IN('" . join("','", $macAddresses) . "')");
    }

    /**
     * @param $locations
     */
    public static function updateClientCoordinates($locations)
    {
        $count = count($locations);

        if (0 === $count) {
            return;
        }

        $join = '';

        for ($i = 0; $i < $count; $i++) {
            if (env('SCHOOL_ID')) {
                $locations[$i]['school_id'] = env('SCHOOL_ID');
            }

            $join .= "("
                . build_sql_parameters(
                    array_map_keys(
                        $locations[$i],
                        [
                        'x' => 'x',
                        'y' => 'y',
                        'floor_id' => 'floor_id',
                        'school_id' => 'school_id',
                        'mac_address' => 'mac_address'
                        ]
                    )
                )
                . ", CURRENT_TIMESTAMP, 1),";
        }

        $join = rtrim($join, ',');

        \DB::insert(
            "
          INSERT INTO devices (`x`, `y`, `floor_id`, `school_id`, `mac_address`, `updated_at`, `active`) VALUES $join
          ON DUPLICATE KEY UPDATE
          `x` = VALUES(`x`),
          `y` = VALUES(`y`),
          `floor_id` = VALUES(`floor_id`),
          `school_id` = VALUES(`school_id`),
          `mac_address` = VALUES(`mac_address`),
          `updated_at` = CURRENT_TIMESTAMP,
          `active` = 1;
        "
        );
    }

    /**
     * Delete all clients.
     */
    public static function deleteAllClients()
    {
      Device::truncate();
    }

    /**
     * @param array $device
     * @return bool|int
     */
    public function update(array $device = array())
    {
        // if multi-shelter is disabled, set the
        // shelter ID to the configured one
        if (isset($device['school_id']) && env('SCHOOL_ID')) {
            $device['school_id'] = env('SCHOOL_ID');
        }

        return parent::update($device);
    }
}
