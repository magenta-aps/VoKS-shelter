<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Api;

use BComeSafe\Events\AlarmWasTriggered;
use BComeSafe\Events\AskedToCallPolice;
use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Http\Requests\GotItRequest;
use BComeSafe\Http\Requests\RegisterDeviceRequest;
use BComeSafe\Http\Requests\TriggerAlarmRequest;
use BComeSafe\Http\Requests\WatchdogRequest;
use BComeSafe\Http\Requests\SheltersRequest;
use BComeSafe\Http\Requests\BcsRequest;
use BComeSafe\Models\Device;
use BComeSafe\Models\History;
use BComeSafe\Models\Log;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SchoolStatus;
use BComeSafe\Models\Watchdog;
use BComeSafe\Packages\Websocket\ShelterClient;

/**
 * Class DeviceController
 *
 * @package BComeSafe\Http\Controllers\Api
 */
class DeviceController extends Controller
{
  
    const COORDINATES_UNAVAILABLE = 1;
    const COORDINATES_NOT_MAPPED  = 2;
    
    public function __construct()
    {
        $this->middleware('device.api');
        $this->websockets = new ShelterClient(config('alarm.php_ws_url') . '/' . config('alarm.php_ws_client'));
    }

    /**
     * @param \BComeSafe\Http\Requests\RegisterDeviceRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function anyRegisterDevice(RegisterDeviceRequest $request)
    {
        try {
            /**
             * @var $device \BcomeSafe\Models\Device
             */
            $device_type = $request->get('device_type');
            $device_id = $request->get('device_id') . "_" . $device_type;
            //Search in Database
            $device_data = Device::getByDeviceId($device_id);
            $id = !empty($device_data['id']) ? $device_data['id'] : null;
            //Find or Create model
            $device = Device::findOrNew($id);
            //Set Attributes
            $device->setAttribute('device_type', $device_type);
            $device->setAttribute('device_id', $request->get('device_id'));
            $mac_address = $request->get('mac_address', config('alarm.default.mac'));
            //Iphone and Pcapp exceptions
            if ($mac_address == '00:00:00:00:00' || $device_type == 'desktop') {
              $mac_address = NULL;
            }
            $device->setAttribute('mac_address', $mac_address);
            $device->setAttribute('push_notification_id', $request->get('gcm_id'));
            $device->setAttribute('fullname', $request->get('user_name'));
            $device->setAttribute('user_email', $request->get('user_email'));
            $device->updateDeviceProfile();

        } catch (\Exception $e) {
            //@Todo - remove this.
            $message = $e->getMessage();

            switch ($e->getCode()) {
                case self::COORDINATES_UNAVAILABLE:
                    $message = \Lang::get('aruba.ale.errors.unavailable', [], $request->get('lang', 'en'));
                    break;
                case self::COORDINATES_NOT_MAPPED:
                    $message = \Lang::get('aruba.ale.errors.unsynchronized', [], $request->get('lang', 'en'));
                    break;
            }

            return response()->json(
                [
                'success' => false,
                'message' => $message,
                ]
            );
        }

        $urls = get_shelter_urls($device->school_id, $device->device_id);

        //Log
        $log_data = array(
          'device_id' => $device_id,
          'device_type' => $device_type,
          'data' => Device::mapDeviceCoordinates($device)->toArray()
        );
        Log::create($log_data);
            
        try {
            $this->websockets->profile(Device::mapDeviceCoordinates($device));
        } catch (\Exception $e) {
            return response()->json(
                [
                'success' => false,
                'message' => \Lang::get('errors.websockets.down', [], $request->get('lang', 'en'))
                ]
            );
        }

        $default = SchoolDefault::getDefaults();

        return response()->json(
            [
            'shelter_id' => $device->school_id,
            'ws_url' => $urls['ws'],
            'api_url' => $urls['api'],
            'message' => null,
            'success' => true,
            'dev_mode' => false,
            'use_gps'  => $default->is_gps_location_source ? true : false,
            'renew'    => false //@Todo - make possible to enable Temporary. Will be used to re-check BCS projects URL.
            ]
        );
    }

    /**
     * @param \BComeSafe\Http\Requests\TriggerAlarmRequest $request
     *
     * @return array
     */
    public function anyTriggerAlarm(TriggerAlarmRequest $request)
    {
        // Get all request data
        $device = $request->only(['device_id', 'call_police']);
        $device['trigger_status'] = $device['call_police'];
        unset($device['call_police']);

        // Check if device has triggered the alarm
        $triggered = false;
        if ('0' === $device['trigger_status']) {
            $triggered = true;
        }

        $device = Device::updateOnTrigger($device);

        $status = (int)\Shelter::getInitiationStatus($device['school_id']);

        // update shelter activity timestamp
        SchoolStatus::updateActivity($device['school_id']);

        // collect shelter stats
        $settings = School::getSettings($device['school_id']);
        $stats = \Shelter::getStats($device['school_id'], $settings->timezone);

        // update shelter stats
        $this->websockets->update($device['school_id'], $stats);

        // PC App since pc app's status is not cached in WS
        // we need to manually send it on PC App restart
        if ($triggered && $device['trigger_status'] == Device::ASKED_TO_CALL) {
            $this->websockets->trigger($device['school_id'], $device['device_id']);
        }

        switch ($device['trigger_status']) {
            case Device::TRIGGERED:
                // if it's the first trigger, fire up the events
                if (!$device['already_triggered'] && $status === Device::TRIGGERED) {
                    \Event::fire(new AlarmWasTriggered($device['school_id']));
                }

                // the desktop app may reconnect on return from hibernation, pc restart etc
                // therefore the cache maybe be clean but we still need to notify the shelter
                // about its connection
                if (!$device['already_triggered'] || $device['device_type'] === 'desktop') {
                    $this->websockets->trigger($device['school_id'], $device['device_id']);
                }

                $response = ['success' => true, 'calling' => false, 'status' => $status];
                break;

            case Device::ASKED_TO_CALL:
                if (!$device['already_triggered'] && $status === Device::ASKED_TO_CALL) {
                    \Event::fire(new AskedToCallPolice($device['school_id']));
                }

                $response = ['success' => true, 'calling' => false, 'asked' => true, 'status' => $status];
                break;

            case Device::CALLED:
                $response = ['success' => true, 'calling' => true, 'status' => $status];
                break;

            default:
                $response = ['success' => false];
                break;
        }

        return $response;
    }

    /**
     * @param \BComeSafe\Http\Requests\GotItRequest $request
     *
     * @return array
     */
    public function anyGotIt(GotItRequest $request)
    {
        $data = $request->all();
        History::gotIt($data['device_id'], $data['notification_id']);

        return ['success' => true];
    }

    /**
     * @param \BComeSafe\Http\Requests\WatchdogRequest $request
     *
     * @return array
     */
    public function anyLogger(WatchdogRequest $request)
    {
        $log_data = $request->only(['device_id', 'device_type']);
        $log_data['data'] = $request->get('message');
        Log::create($log_data);

        return ['success' => true];
    }

    /**
     * @param \BComeSafe\Http\Requests\SheltersRequest $request
     *
     * @return json
     */
    public function anyShelters(SheltersRequest $request)
    {
        $ret_val = array();
        $list = School::get()->toArray();
        if (empty($list)) {
          return response()->json($ret_val);
        }

        foreach ($list as $s) {
          $s['use_gps'] = $s['use_gps'] == 1 ? TRUE : FALSE;
          $ret_val[] = array_map_keys(
            $s,
            [
              'shelter_id'           => 'id',
              'shelter_name'         => 'name',
              'shelter_url'          => 'url',
              'police_number'        => 'police_number',
              'use_gps'              => 'use_gps'
            ]
          );
        }

        return response()->json($ret_val);
    }
	
	    /**
     * @param \BComeSafe\Http\Requests\BcsRequest $request
     *
     * @return json
     */
    public function anyList(BcsRequest $request)
    {
        $ret_val = array();
        $list = School::where('display', '=', '1')->where('public', '=', '1')->where('ip_address', '=', \Request::ip())->get()->toArray();
        if (empty($list)) {
          $list = School::where('display', '=', '1')->where('public', '=', '0')->get()->toArray();
          if (empty($list)) {
            return response()->json($ret_val);
          }
        }

        foreach ($list as $s) {
          $ret_val[] = array_map_keys(
            $s,
            [
              'bcs_id'               => 'id',
              'bcs_name'             => 'name',
              'bcs_url'              => 'url',
              'police_number'        => 'police_number',
              'public'               => 'public'
            ]
          );
        }

        return response()->json($ret_val);
    }

}
