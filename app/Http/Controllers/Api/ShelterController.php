<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Api;

use BComeSafe\Commands\SendPushNotifications;
use BComeSafe\Events\AlarmWasTriggered;
use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Models\ClientDevice;
use BComeSafe\Models\Device;
use BComeSafe\Models\Faq;
use BComeSafe\Models\GotItHistory;
use BComeSafe\Models\HelpFile;
use BComeSafe\Models\History;
use BComeSafe\Models\PushNotification;
use BComeSafe\Models\PushNotificationMessage;
use BComeSafe\Models\School;
use BComeSafe\Models\Floor;
use BComeSafe\Models\SchoolStatus;
use BComeSafe\Models\SentPushNotification;
use BComeSafe\Models\TriggeredDevice;
use BComeSafe\Packages\Notifications;
use BComeSafe\Packages\Websocket\ShelterClient;
use Illuminate\Http\Request;
use Shelter;
use Zend\Http\PhpEnvironment\Response;


/**
 * Class ShelterController
 *
 * @package BComeSafe\Http\Controllers\Api
 */
class ShelterController extends Controller
{
    /**
     * @param null $id
     *
     * @return array
     */
    public function getStats($id = null)
    {
        if (!$id) {
            $id = Shelter::getID();
        }

        $defaults = School::getSettings($id);
        $stats = Shelter::getStats($id, $defaults->timezone);

        return $stats;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getHistory($id)
    {
        // Result array
        $return = [];

        // Update school activity and get its settings
        SchoolStatus::updateActivity($id);
        $settings = School::getSettings($id);

        // Get all Shelter history messages
        $history = History::where('school_id', '=', $id)->orderBy('id', 'desc')->get();
        if (0 < count($history)) {
            foreach ($history as $value) {
                // Convert model to array and set proper time
                $item = $value->toArray();
                $item['time'] = date('H:i', strtotime($value->updated_at->timezone($settings->timezone)));

                // Process by type
                switch ($item['type']) {
                    case 'push':
                        $item = $this->historyProcessPush($item);
                        break;

                    case 'sms':
                        $item = $this->historyProcessSms($item);
                        break;

                    case 'trigger':
                    case 'play':
                    case 'live':
                        $item = $this->historyProcessAudio($item);
                        break;
                }

                $return[] = $item;
            }
        }

        return $return;
    }

    /**
     * Processes push notification history item
     *
     * @param array $item History item
     *
     * @return array Processed item
     */
    private function historyProcessPush($item)
    {
        $result = json_decode($item['result'], true);

        $item['type'] = \Lang::get('shelter/history.push.title');
        $item['result'] = \Lang::get(
            'shelter/history.push.result',
            [
                'count' => $result['read'],
                'total' => $result['total']
            ]
        );

        return $item;
    }

    /**
     * Processes SMS history item
     *
     * @param array $item History item
     *
     * @return array Processed item
     */
    private function historyProcessSms($item)
    {
        $result = json_decode($item['result'], true);

        $item['type'] = \Lang::get('shelter/history.sms.title');
        $item['result'] = \Lang::get(
            'shelter/history.sms.result',
            [
                'count' => $result['count'],
                'total' => $result['total']
            ]
        );

        return $item;
    }

    /**
     * Processes SMS history item
     *
     * @param array $item History item
     *
     * @return array Processed item
     */
    private function historyProcessAudio($item)
    {
        $result = json_decode($item['result'], true);
        $played = (true === $result['status']) ? 'success' : 'error';
        $namespace = 'shelter/history.audio.' . $item['type'] . '.';

        $params = [];
        switch ($item['type']) {
            case 'play':
            case 'trigger':
                // No voice - show translation
                if (null === $result['voice']) {
                    $result['voice'] = \Lang::get($namespace . 'default.voice');
                }

                // No group - show translation
                if (null === $result['group']) {
                    $result['group'] = \Lang::get($namespace . 'default.group');
                }

                // Updated params
                $params = [
                    'voice' => $result['voice'],
                    'group' => $result['group']
                ];
                break;

            case 'live':
                // No group - show translation
                if (null === $result['group']) {
                    $result['group'] = \Lang::get($namespace . 'default.group');
                }

                // Updated params
                $params = [
                    'number' => $result['number'],
                    'group'  => $result['group']
                ];
                break;
        }

        $item['type'] = \Lang::get($namespace . 'title');
        $item['message'] = \Lang::get($item['message'], $params);
        $item['result'] = \Lang::get($namespace . 'result.' . $played);

        return $item;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getClient($id)
    {
        $device = Device::where('device_id', '=', $id)->first();
        $device['called'] = false;

        if (!empty($device['call_police'])) {
            switch ($device['call_police']) {
                case Device::CALLED:
                case Device::ASKED_TO_CALL:
                    $device['called'] = true;
                    break;
            }
        }

        return $device;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getResetPoliceCallers($id)
    {
        // update all devices
        Device::updateAllToNotCalled($id);

        // send updated shelter stats
        $websockets = new ShelterClient(config('alarm.php_ws_url') . '/' . config('alarm.php_ws_client'));
        $websockets->update($id, $this->getStats($id));

        return ['success' => true];
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getReset($id)
    {
        //send out shelter reset message to all clients
        $websockets = new ShelterClient(config('alarm.php_ws_url') . '/' . config('alarm.php_ws_client'));
        $websockets->reset($id);

        // School status
        SchoolStatus::statusAlarm($id, 0);
        SchoolStatus::statusPolice($id, 0);

        // reset device statuses
        Device::updateAllToInactive($id);

        // clear history for the last alarm
        History::truncateForShelter($id);

        // clear got it history for the last alarm
        GotItHistory::truncateForShelter($id);

        return ['success' => true];
    }

    /**
     * Update Shelter police status.
     * Triggers the alarm if necessary.
     *
     * @param Request $request
     *
     * @return Response JSON encoded Shelter status
     */
    public function postPolice(Request $request)
    {
        // Params
        $status = $request->input('status');
        $shelterId = Shelter::getID();

        switch ($status) {
            // Cancel police
            case 0:
                $this->policeOff();
                break;

            // Call police [trigger alarm]
            case 1:
                $this->policeOn();
                break;
        }

        // Get current Shelter status
        $status = $this->getStats($shelterId);

        // Send updated shelter stats
        $websockets = new ShelterClient(config('alarm.php_ws_url') . '/' . config('alarm.php_ws_client'));
        $websockets->update($shelterId, $status);

        // Return Shelter status
        return response()->json($status);
    }

    /**
     * Call police.
     * Triggers the alarm if necessary.
     */
    private function policeOn()
    {
        $shelterId = Shelter::getID();
        $schoolStatus = SchoolStatus::where('school_id', '=', $shelterId)->first();

        // Trigger the alarm
        if (0 === $schoolStatus->status_alarm) {
            SchoolStatus::statusAlarm($shelterId, 1);

            \Event::fire(new AlarmWasTriggered($shelterId));
        }

        SchoolStatus::statusPolice($shelterId, 1);
    }

    /**
     * Cancel police.
     */
    private function policeOff()
    {
        $shelterId = Shelter::getID();

        Device::updateAllToNotCalled($shelterId);
        SchoolStatus::statusPolice($shelterId, 0);
    }

    /**
     * @param null $id
     *
     * @return mixed
     */
    public function getPushTemplates($id = null)
    {
        return PushNotificationMessage::where('school_id', '=', $id)->orderBy('order', 'asc')->get();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getUserProfile($id)
    {
        $device = Device::where('device_id', '=', $id)->first();
        $device['called'] = false;

        switch ($device['call_police']) {
            case Device::CALLED:
            case Device::ASKED_TO_CALL:
                $device['called'] = true;
                break;
        }

        return $device;
    }

    /**
     * Gets coordinates
     *
     * ALTER TABLE `devices` ADD INDEX( `school_id`, `active`);
     * ALTER TABLE `devices` ADD INDEX( `floor_id`, `active`);
     *
     * @param Request $request
     *
     * @return Response JSON encoded response
     */
    public function getCoordinates(Request $request)
    {

        if ($request->has('list')) {
            $macs = $request->get('list');

            $list = [];
            $i = 1;

            foreach ($macs as $mac) {
                $list['mac:' . $i] = $mac;
            }

            \Artisan::call('aruba:sync:macs', $list);

            $devices = Device::whereIn('mac_address', $macs)->get();
        } else {
            // fetch all clients that are active and belong to this school

            $floors = Floor::where('school_id', '=', \Shelter::getID())->get();
            $floorIds = [];

            foreach ($floors as $floor) {
                $floorIds[] = $floor->id;
            }

            $devices =
                Device::where('active', '=', 1)
                      ->whereIn('floor_id', $floorIds)->get();
        }

        $response = [];
        for ($i = 0; $i < count($devices); $i++) {
            $response[] = Device::mapDeviceCoordinates($devices[$i]);
        }

        SchoolStatus::updateActivity();

        return response()->json($response);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getHelp($id)
    {
        $faq = Faq::where(
            [
                'school_id' => $id,
                'visible'   => '1'
            ]
        )->orderBy('order', 'ASC')->get();

        $files = HelpFile::getFile();

        return [
            'faq'   => $faq,
            'files' => $files
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function postSendPush(Request $request)
    {
        $devices = [];
        $clients = $request->get('clients');
        $message = $request->get('message');

        foreach ($clients as $client) {
            $devices[] = ['id' => $client['gcm_id'], 'type' => $client['type']];
        }

        $notification = SentPushNotification::create(
            [
                'school_id' => Shelter::getID(),
                'message'   => $message
            ]
        );

        History::pushNotification($notification, count($devices));

        \Queue::push(new SendPushNotifications($devices, $message, $notification->id));

        return $devices;
    }

    /**
     * @return mixed
     */
    public function getQuickHelp()
    {
        return trans('tour');
    }

    /**
     * @param $shelterId
     *
     * @return array
     */
    public function getMaps($shelterId)
    {
        return School::getMaps($shelterId);
    }
}
