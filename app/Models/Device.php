<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SchoolDefaultFields;
use BComeSafe\Packages\Aruba\Ale\AleLocation;
use BComeSafe\Packages\Aruba\ArubaControllers\ArubaControllers;
use BComeSafe\Packages\Cisco\Cmx\Location\CmxLocation;
use BComeSafe\Packages\Coordinates\Coordinates;
use BComeSafe\Packages\Coordinates\IntegrationException;
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
     *
     */
    const COORDINATES_UNAVAILABLE = 1;
    /**
     *
     */
    const COORDINATES_NOT_MAPPED  = 2;

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
        'ip_address',
        'x',
        'y',
        'active',
        'floor_id',
        'triggered_at',
        'user_email',
        'ap_name'
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
    public static function getByDeviceId($deviceId)
    {
        $device = static::where('device_id', '=', $deviceId)->first(
            [
                'id', 'mac_address', 'device_id', 'fullname', 'push_notification_id', 'device_type', 'x', 'y', 'username'
            ]
        );

        if ($device) {
            return $device->toArray();
        }

        return [];
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
        } elseif (empty($this->getAttribute('fullname') )) {
          if (!empty($this->getAttribute('username'))) {
            $this->attributes['fullname'] = $this->getAttribute('username');
          } else if (!empty($this->getAttribute('mac_address'))) {
            $this->attributes['fullname'] = $this->getAttribute('mac_address') . '(' . ucfirst(strtolower($this->getAttribute('device_type'))) . ')';
          } else {
            $this->attributes['fullname'] = ucfirst(strtolower($this->getAttribute('device_type')));
          }
        }
    }

    /**
     * @return array|mixed
     * @throws IntegrationException
     */
    protected function getProfileData()
    {
      $device = [];
      $default = SchoolDefault::getDefaults();
      //
      if (!empty($default->client_data_source)) {
        //Aruba
        if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA) {
          //Aruba Clearpass
          if (config('aruba.clearpass.enabled')) {
            $ArubaClearpass = new User();
            if (!$this->getAttribute('mac_address')) {
              $device = $ArubaClearpass->getByIp($this->getAttribute('ip_address'));
            } else {
              $device = $ArubaClearpass->getByMac($this->getAttribute('mac_address'));
            }
          }
          //Aruba Controller
          if (config('aruba.controllers.enabled')) {
            $AurbaControllers = new ArubaControllers();
            $ap_name = $AurbaControllers->getAPByIp($this->getAttribute('ip_address'));
            if (!empty($ap_name)) {
              $device['ap_name'] = $ap_name;
            }
          }
          if (!isset($device['mac_address'])) {
            throw new IntegrationException('Couldn\'t fetch the MAC Address. Are you sure you\'re connected to Wifi?');
          }
        }
        //Cisco CMX
        if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO && config('cisco.enabled')) {
          if (!empty($this->getAttribute('mac_address'))) {
            $device['mac_address'] = $this->getAttribute('mac_address');
          }
          else {
            $device['mac_address'] = CmxLocation::getMacAddressByIP($this->getAttribute('ip_address'));
          }
          if (!isset($device['mac_address'])) {
            throw new IntegrationException('Couldn\'t fetch the MAC Address. Are you sure you\'re connected to Wifi?');
          }
        }
      }
      
      return $device;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getLocationData()
    {

        $device = [];
        $school_id = School::getDefaultSchoolID();
        $default = SchoolDefault::getDefaults();
        //Single Shelter
        if ($school_id) {
            $overwrite = false;
            //School ID has changed - need to overwrite floor and coordinates.
            if ($this->getAttribute('school_id') && $this->getAttribute('school_id') != $school_id) {
              $overwrite = true;
            }
            if (!$this->getAttribute('floor_id')) {
              $overwrite = true;
            }
            
            if ($overwrite) {
              $floor = Floor::where('school_id', '=', $school_id)->orderBy('id', 'desc')->get()->first();
              //Aruba Airwave
              if (empty($floor) && $default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA && config('aruba.airwave.enabled')) {
                throw new \Exception(
                    'Structure not synchronized on campus #' . $school_id . '. Please wait.'
                );
              }
              //Cisco CMX
              if (empty($floor) && $default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO && config('cisco.enabled')) {
                throw new \Exception(
                    'Structure not synchronized on campus #' . $school_id . '. Please wait.'
                );
              }
              $device['school_id'] = $school_id;
              $device['floor_id'] = isset($floor->id) ? $floor->id : 0;
              $device['x'] = 0;
              $device['y'] = 0;
            }

            $device['active'] = 1;

            if (\Request::get('test')) {
                $device['x'] = mt_rand(100, 500);
                $device['y'] = mt_rand(100, 500);
            }
            return $device;
            
        //Multi Shelter
        } else {
            //Aruba Clearpass or Controllers
            if (config('aruba.controllers.enabled') || config('aruba.clearpass.enabled')) {
              //Access Point name is updated from Controller or Clearpass
              $ap_name = $this->getAttribute('ap_name');
              if (!empty($ap_name)) {
                $ap = Aps::where('ap_name', '=', $ap_name)->get()->first();
                $device['school_id'] = $ap->school_id;
                $device['floor_id'] = $ap->floor_id;
                $device['x'] = $ap->x;
                $device['y'] = $ap->y;
                $device['active'] = 1;
              }
              return $device;
            }
            //Aruba ALE
            if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ARUBA && config('aruba.ale.enabled')) {
              $location = AleLocation::getCoordinates($this->getAttribute('mac_address'));
              if (!empty($location['campus_id'])) {
                  $floor = Floor::where('floor_hash_id', '=', $location['floor_id'])->first();

                  if (isset($floor->building->campus->school_id)) {
                      $device['school_id'] = $floor->building->campus->school_id;
                      $device['floor_id'] = $floor->id;
                  } else {
                      static::deactivate($this->getAttribute('device_id'));

                      throw new \Exception(
                          'There are no coordinates or campuses not synchronized. Please wait.',
                          self::COORDINATES_NOT_MAPPED
                      );
                  }
              } else {
                  static::deactivate($this->getAttribute('device_id'));

                  throw new \Exception(
                      'We could not locate you via ALE. Please wait.',
                      self::COORDINATES_UNAVAILABLE
                  );
              }
              $device['x'] = $location['x'];
              $device['y'] = $location['y'];
              $device['active'] = 1;
              return $device;
            }
            //Cisco CMX
            if ($default->client_data_source == SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO && config('cisco.enabled')) {
              $location = CmxLocation::getCoordinates($this->getAttribute('mac_address'));
              if (!empty($location['floor_id'])) {
                  $floor = Floor::where('floor_hash_id', '=', $location['floor_id'])->first();

                  if (isset($floor->building->campus->school_id)) {
                      $device['school_id'] = $floor->building->campus->school_id;
                      $device['floor_id'] = $floor->id;
                  } else {
                      static::deactivate($this->getAttribute('device_id'));

                      throw new \Exception(
                          'There are no coordinates or campuses not synchronized. Please wait.',
                          self::COORDINATES_NOT_MAPPED
                      );
                  }
              } else {
                  static::deactivate($this->getAttribute('device_id'));

                  throw new \Exception(
                      'We could not locate you via ALE. Please wait.',
                      self::COORDINATES_UNAVAILABLE
                  );
              }
              $device['x'] = $location['x'];
              $device['y'] = $location['y'];
              $device['username'] = $location['username'];
              $device['fullname'] = $location['username'];
              $device = array_merge_filled(
                  $device,
                  Coordinates::convert(
                      $floor['image']['pixel_width'],
                      $floor['image']['real_width'],
                      $floor['image']['pixel_height'],
                      $floor['image']['real_height'],
                      $device['x'],
                      $device['y']
                  )
              );
              $device['active'] = 1;
            }
        }
        return $device;
    }

    /**
     * @throws IntegrationException
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

        $before = (int) @$client->trigger_status;
        $now = $data['trigger_status'];

        if ($before >= $now) {
            return [
                'trigger_status'    => $client->trigger_status,
                'already_triggered' => true
            ];
        }

        return $data;
    }

    public static function deactivate($deviceId)
    {
        $device = Device::where('device_id', '=', $deviceId)->first();
        if (!empty($device)) {
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
    public static function mapDeviceCoordinates($device, $show = true)
    {
        $name = '';
        if (!$show) {
          $name = ucfirst(strtolower($device->device_type));
        } else {
          if (!empty($device->fullname)) {
            $name = $device->fullname;
          } elseif (!empty($device->username)) {
            $name = $device->username;
          } else {
            $name = $device->mac_address . ' (' . ucfirst(strtolower($device->device_type)) . ')';
          }
        }

        return [
            'profile'  => [
                'name'        => $name,
                'mac_address' => $device->mac_address,
                'device'      => $device->device_type,
                'device_id'   => $device->device_id,
                'gcm_id'      => $device->push_notification_id
            ],
            'position' => [
                'x'        => $device->x,
                'y'        => $device->y,
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
      if (empty($locations)) {
        return 0;
      }
      $i=0;
      $school_id = School::getDefaultSchoolID();
      foreach ($locations as $l) {
        if ($school_id) {
          $l['school_id'] = $school_id;
        }
        $device = \DB::select(
          "SELECT * FROM devices WHERE mac_address = :mac_address" , ['mac_address' => $l['mac_address']]
        );
        if (!empty($device)) {
          $device = current($device);
          if(
            $l['x'] != $device->x
            || $l['y'] != $device->y
            || $l['floor_id'] != $device->floor_id
            || $l['school_id'] != $device->school_id
            || $l['username'] != $device->username
            || $l['fullname'] != $device->fullname
            || $l['ap_name'] != $device->ap_name
          ) {
            $l['id'] = $device->id;
            unset($l['mac_address']);
            \DB::update(
              "
                UPDATE devices
                SET x = :x, y = :y, floor_id = :floor_id, school_id = :school_id, username = :username, fullname = :fullname, ap_name = :ap_name, updated_at = CURRENT_TIMESTAMP, active = 1
                WHERE id = :id
              ",
              $l
            );
            $i++;
          }
        }
      }
      return $i;
    }

    /**
     * Delete all clients.
     */
    public static function deleteAllClients()
    {
      Device::truncate();
    }
    
    /**
     * @param $clients
     */
    public static function updateClients($clients)
    {
        if (empty($clients)) {
          return false;
        }
        //Deactivate all devices
        \DB::update("UPDATE devices SET active = 0 WHERE active = 1");
        //Activate only active devices
        foreach ($clients as $client) {
          if (env('SCHOOL_ID')) {
            $client['school_id'] = env('SCHOOL_ID');
          }
          //Search for the client
          $device = \DB::select(
            "SELECT id, x, y, floor_id, school_id FROM devices WHERE mac_address = :mac_address" , ['mac_address' => $client['mac_address']]
          );
          //Found - update
          if (!empty($device)) {
            $device = current($device);
            if(
              $client['x'] != $device->x
              || $client['y'] != $device->y
              || $client['floor_id'] != $device->floor_id
              || $client['school_id'] != $device->school_id
            ) {
              //
              $client['id'] = $device->id;
              //$client['fullname'] = $client['username'];
              \DB::update(
                "
                  UPDATE devices
                  SET
                    x = :x,
                    y = :y,
                    floor_id = :floor_id,
                    school_id = :school_id,
                    mac_address = :mac_address,
                    username = :username,
                    fullname = :fullname,
                    updated_at = CURRENT_TIMESTAMP,
                    active = 1
                  WHERE id = :id
                ", $client
              );
            }
          }
          //Not found - insert
          else {
            $join = "(";
            $join .= "'" . $client['mac_address'] . "'";
            $join .= ", '" . $client['floor_id'] . "'";
            $join .= ", '" . $client['school_id'] . "'";
            $join .= ", '" . $client['username'] . "'";
            $join .= ", '" . $client['username'] . "'";
            $join .= ", '" . $client['x'] . "'";
            $join .= ", '" . $client['y'] . "'";
            $join .= ', 1';
            $join .= ', CURRENT_TIMESTAMP';
            $join .= ")";

            \DB::insert("
              INSERT INTO devices (
                `mac_address`,
                `floor_id`,
                `school_id`,
                `username`,
                `fullname`,
                `x`,
                `y`,
                `active`,
                `updated_at`
              )
              VALUES $join
            ");
          }
        }
        return true;
    }

    /**
     * @param array $device
     * @return bool|int
     */
    public function update(array $device = array())
    {
        // if multi-shelter is disabled, set the
        // shelter ID to the configured one
        $school_id = School::getDefaultSchoolID();
        if (isset($device['school_id']) && $school_id) {
            $device['school_id'] = $school_id;
        }

        return parent::update($device);
    }
}
