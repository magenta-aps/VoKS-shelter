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
use BComeSafe\Events\PoliceWasCalled;
use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Http\Requests\GotItRequest;
use BComeSafe\Http\Requests\RegisterDeviceRequest;
use BComeSafe\Http\Requests\TriggerAlarmRequest;
use BComeSafe\Http\Requests\WatchdogRequest;
use BComeSafe\Http\Requests\SheltersRequest;
use BComeSafe\Http\Requests\BcsRequest;
use BComeSafe\Http\Requests\SaveDeviceRequest;
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
            $device_id = $request->get('device_id');
            //Language
            $lang = !empty($request->get('lang')) ? $request->get('lang') : 'en';
            //Ios workaround
            if ($lang == 'nb') {
              $lang = 'no';
            }
            //Search in Database
            $device_data = Device::getByDeviceId($device_id);
            $id = !empty($device_data['id']) ? $device_data['id'] : null;
            //Find or Create model
            $device = Device::findOrNew($id);
            //Set Attributes
            $device->setAttribute('device_type', $device_type);
            $device->setAttribute('device_id', $device_id);
            $mac_address = $request->get('mac_address', config('alarm.default.mac'));
            //Iphone exceptions
            if (!config('alarm.use_mac_address_for_ios') && $mac_address == '00:00:00:00:00') {
              $mac_address = NULL;
            }
            //Pcapp exceptions
            if (!config('alarm.use_mac_address_for_pcapp') && $device_type == 'desktop') {
              $mac_address = NULL;
            }
            //Android exceptions
            if (!config('alarm.use_mac_address_for_android') && $device_type == 'android') {
              $mac_address = NULL;
            }
            $device->setAttribute('mac_address', $mac_address);
            $device->setAttribute('push_notification_id', $request->get('gcm_id'));
            $device->setAttribute('fullname', $request->get('user_name'));
            $device->setAttribute('user_email', $request->get('user_email'));
            //For debuging IP address
            $ip_address = !empty($request->get('ip_address')) ? $request->get('ip_address') : \Request::ip();
            $device->setAttribute('ip_address', $ip_address);
            $device->updateDeviceProfile();

        } catch (\Exception $e) {
            $message = $e->getMessage();

            switch ($e->getCode()) {
                case Device::COORDINATES_UNAVAILABLE:
                    $message = \Lang::get('aruba.ale.errors.unavailable', [], $lang);
                    break;
                case Device::COORDINATES_NOT_MAPPED:
                    $message = \Lang::get('aruba.ale.errors.unsynchronized', [], $lang);
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
        if (config('app.debug')) {
          $log_data = array(
            'device_id' => $device_id,
            'device_type' => $device_type,
            'data' => json_encode(Device::mapDeviceCoordinates($device), SchoolStatus::getStatusAlarm($device->school_id))
          );
          Log::create($log_data);
        }
            
        try {
            $this->websockets->profile(Device::mapDeviceCoordinates($device, SchoolStatus::getStatusAlarm($device->school_id)));
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
            'renew'    => $device->renew,
            'user_phone' => $device->user_phone,
            'user_phone_token' => $device->user_phone_token,
            'user_phone_confirm' => $device->user_phone_confirm,
            'need_phone' => $device->need_phone,
            'need_tac' => $device->need_tac,
            'mac_address' => $device->mac_address,
            'ap_name' => $device->ap_name,
            'ip_address' => $device->ip_address,
            'role' => $device->role,
            'tac_text' => \Lang::get('app.tac.default', [], $lang) //@Todo - make administrated.
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
        //Temporary fix for IOS devices
        if (substr($device['device_id'], -8) == '_ios_ios') {
          $device['device_id'] = substr($device['device_id'], 0, -4);
        }
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

        // Update client profile, because names are not showed until alarm is not triggered.
        if ($device['trigger_status'] === Device::TRIGGERED && !$device['already_triggered'] && $status === Device::TRIGGERED) {
          $device_db = Device::where('device_id', '=', $device['device_id'])->get()->first();
          $this->websockets->profile(Device::mapDeviceCoordinates($device_db, $status));
        }
        
        // update shelter stats
        $this->websockets->update($device['school_id'], $stats);

        // PC App since pc app's status is not cached in WS
        // we need to manually send it on PC App restart
        if ($triggered && $device['trigger_status'] == Device::ASKED_TO_CALL) {
            $this->websockets->trigger($device['school_id'], $device['device_id']);
        }

        switch ($device['trigger_status']) {
            case Device::TRIGGERED:
                // the desktop app may reconnect on return from hibernation, pc restart etc
                // therefore the cache maybe be clean but we still need to notify the shelter
                // about its connection
                if (!$device['already_triggered'] || $device['device_type'] === 'desktop') {
                    $this->websockets->trigger($device['school_id'], $device['device_id']);
                }
                
                // if it's the first trigger, fire up the events
                if (!$device['already_triggered'] && $status === Device::TRIGGERED) {
                    \Event::fire(new AlarmWasTriggered($device['school_id'], $device['device_id']));
                    //Set School status
                    SchoolStatus::statusAlarm($device['school_id'], $status);
                }

                $response = ['success' => true, 'calling' => false, 'status' => $status];
                break;

            case Device::ASKED_TO_CALL:
                if (!$device['already_triggered'] && $status === Device::ASKED_TO_CALL) {
                    // TODO fire event regardless of whether device has already triggered?
                    \Event::fire(new AskedToCallPolice($device['school_id'], $device['device_id']));
                }

                $response = ['success' => true, 'calling' => false, 'asked' => true, 'status' => $status];
                break;

            case Device::CALLED:
                $response = ['success' => true, 'calling' => true, 'status' => $status];
                \Event::fire(new PoliceWasCalled($device['school_id'], $device['device_id']));
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
    
    /**
     * @param \BComeSafe\Http\Requests\SaveDeviceRequest $request
     *
     * @return array
     */
    public function postUpdateDevice(SaveDeviceRequest $request)
    {
        $update = array();
        //@Todo - use $request->filled('user_phone') 
        $params = $request->all();
        //Log
        if (config('app.debug')) {
          $log_data = array(
            'device_id' => $request->get('device_id'),
            'device_type' => 'Unknown',
            'data' => json_encode($params)
          );
          Log::create($log_data);
        }
        //
        if (isset($params['user_phone'])) {
          if (!empty($request->get('user_phone'))) {
            $update['user_phone'] = $request->get('user_phone');
            $update['need_phone'] = 0;
            $update['user_phone_confirm'] = 0;
            //Generate token
            $update['user_phone_token'] = Device::generateToken();
            //Send token via SMS
            $defaults = SchoolDefault::getDefaults();
            if (!empty($defaults->sms_provider)) {
              $integration = \Component::get('Sms')->getIntegration($defaults->sms_provider);
              $integration->sendMessage($update['user_phone'], $update['user_phone_token']);
            }
          }
          else {
            $update['user_phone'] = '';
            $update['need_phone'] = 1;
            $update['user_phone_confirm'] = 0;
            $update['user_phone_token'] = '';
          }
          
        }
        //
        if (!empty($request->get('skip_phone'))) {
          $update['need_phone'] = 0;
        }
        //
        if (!empty($request->get('user_phone_token'))) {
          $device_data = Device::getByDeviceId($request->get('device_id'));
          if (!empty($device_data['user_phone_token']) && $device_data['user_phone_token'] == $request->get('user_phone_token')) {
            $update['user_phone_confirm'] = 1;
          }
        }
        //
        //@Todo - use $request->filled('accepted_tac') 
        if (isset($params['accepted_tac'])) {
          if (!empty($request->get('accepted_tac'))) {
            $update['need_tac'] = 0;
          }
          else {
            $update['need_tac'] = 1;
          }
        }
        
        if (!empty($update)) {
          Device::findAndUpdate(
            [
              'device_id' => $request->get('device_id')
            ],
            $update
          );
          return ['success' => true];
        }
        return ['success' => false];
    }
}
