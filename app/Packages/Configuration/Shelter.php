<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Configuration;

use BComeSafe\Models\Device;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\SchoolStatus;

/**
 * Class Shelter
 *
 * @package BComeSafe\Packages\Configuration
 */
class Shelter
{
    /**
     * @var int
     */
    protected $id = 0;
    /**
     * @var null
     */
    protected $peer_id = null;

    /**
     * @return int
     */
    public function getID()
    {
        if ($this->id === 0) {
            $school = School::where('ip_address', '=', \Request::ip())->first();
            if ($school) {
                $this->id = $school->id;
            }
        }

        return $this->id;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return 'prod';
    }

    /**
     * @return mixed
     */
    public function getWebsocketServer()
    {
        return get_shelter_urls(\Shelter::getID(), \Shelter::getID())['ws'];
    }

	/**
	 * @return boolean
	 */
	public function getArubaCoordinatesStatus()
    {
	    return config('aruba.ale.coordinatesEnabled');
    }

    /**
     * Manually managed translations for the front-end
     *
     * @return string JSON encoded translations
     */
    public function getTranslations()
    {
        $translations = [
            'header' => [
                // All headings
                'heading' => \Lang::get('header.heading'),

                // Shelter status
                'status' => [
                    'alarm' => [
                        'off' => \Lang::get('header.status.alarm.off'),
                        'on' => \Lang::get('header.status.alarm.on')
                    ],
                    'started' => [
                        'default' => \Lang::get('header.status.started.default')
                    ]
                ]
            ],
            'map' => [
                'zoom' => [
                    'in' => \Lang::get('voks.stream.button.zoom_in'),
                    'out' => \Lang::get('voks.stream.button.zoom_out')
                ]
            ],
            'audio' => [
                'play' => [
                    'toast' => \Lang::get('shelter/audio.play.toast')
                ],
                'live' => [
                    'toast' => \Lang::get('shelter/audio.live.toast')
                ]
            ],
            'toast' => \Lang::get('toast'),
            'quickhelp' => \Lang::get('quickhelp-admin')
        ];
        return json_encode($translations);
    }

    /**
     * Order options
     *
     * @return string
     */
    public function getOrderOptions()
    {
        $options = config('sorting.options');

        $config = School::find($this->getID());
        if (!$config) {
            $config = SchoolDefault::getDefaults();
        }
        $return = [];

        $default = $config->ordering;

        foreach ($options as $key => $value) {
            $row = ['by' => $key, 'title' => \Lang::get($value)];
            if ($default === $key) {
                $row['default'] = true;
            }
            $return[] = $row;
        }

        return json_encode($return);
    }

    /**
     * @return string
     */
    public function getPoliceCallStatus()
    {

        return json_encode([]);
    }

    /**
     * @return int
     */
    public function getStreamBlockLimit()
    {
        $limit = 7;
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        return $limit;
    }

    /**
     * @param null $id
     * @param string $timezone
     * @return array
     */
    public function getStats($id = null, $timezone = 'UTC')
    {
        if (empty($id)) {
            $id = $this->getID();
        }

        $response = ['success' => true];

        $clients = Device::where('school_id', '=', $id)
            ->where('device_id', '<>', '')
            ->where('trigger_status', '>', 0)
            ->orderBy('triggered_at', 'asc')
            ->get([
                'device_id',
                'device_type',
                'triggered_at',
                'push_notification_id',
                'trigger_status',
                'fullname'
            ]);

        $response['time'] = null;
        $response['police_called'] = 0;
        $response['asked_to_call'] = 0;
        $response['callers'] = [];

        $response['stats'] = [
            Device::TRIGGERED => 0,
            Device::ASKED_TO_CALL => 0,
            Device::CALLED => 0
        ];

        $response['clients'] = [];
        $response['status'] = Device::INACTIVE;

        // Check Shelter alarm status
        $shelterStatus = SchoolStatus::where('school_id', '=', $id)->get()->first();
        if ($shelterStatus->status_alarm) {
            $response['time'] = date('H:i', strtotime($shelterStatus->last_active->timezone($timezone)));
            $response['status'] = Device::TRIGGERED;
        }

        // Check Shelter police status
        if ($shelterStatus->status_police) {
            $response['police_called']++;
            $response['stats'][Device::CALLED]++;
            $response['callers'][] = $id;
        }

        for ($i = 0; $i < count($clients); $i++) {
            if ($clients[$i]['trigger_status'] > Device::INACTIVE) {
                $response['clients'][] = $clients[$i]->toArray();
            }

            if (empty($response['time']) && $clients[$i]->trigger_status > Device::INACTIVE) {
                $response['time'] = date('H:i', strtotime($clients[$i]->triggered_at->timezone($timezone)));
                $response['status'] = Device::TRIGGERED;
            }

            switch ($clients[$i]->trigger_status) {
                case Device::CALLED:
                    $response['police_called']++;
                    $response['stats'][Device::CALLED]++;
                    $response['callers'][] = $clients[$i]->device_id;
                    break;
                case Device::ASKED_TO_CALL:
                    $response['asked_to_call']++;
                    $response['stats'][Device::ASKED_TO_CALL]++;
                    $response['callers'][] = $clients[$i]->device_id;
                    break;
                case Device::TRIGGERED:
                    $response['stats'][Device::TRIGGERED]++;
                    break;
            }
        }

        /**
         * Select which status to show based on number of calls
         * and call requests
         */
        if ($response['police_called'] === 0) {
            if ($response['asked_to_call'] > 0) {
                $response['status'] = Device::ASKED_TO_CALL;
            }
        } else {
            $response['status'] = Device::CALLED;
        }

        if (empty($response['time'])) {
            $response['time'] = '';
        }

        return $response;
    }

    /**
     * @param $shelterId
     * @return int
     */
    public function getInitiationStatus($shelterId)
    {
        $stats = $this->getStats($shelterId);

        $firstTrigger = $stats['stats'][Device::TRIGGERED] === 1
            && $stats['stats'][Device::ASKED_TO_CALL] === 0
            && $stats['stats'][Device::CALLED] === 0;

        //device::triggered 1 | 10
        $firstRequest = $stats['stats'][Device::ASKED_TO_CALL] === 1
            && $stats['stats'][Device::CALLED] === 0;

        if ($firstTrigger) {
            return Device::TRIGGERED;
        } elseif ($firstRequest) {
            return Device::ASKED_TO_CALL;
        }

        return Device::INACTIVE;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $school = School::getSettings();
        $config = [];
        $config['peer-id'] = $this->getID();
        $config['id'] = $this->getID();
        $config['mode'] = $this->getMode();
        $config['websocket-server'] = $this->getWebsocketServer();
        $config['translations'] = $this->getTranslations();
        $config['order-options'] = $this->getOrderOptions();
        $config['police'] = $this->getPoliceCallStatus();
        $config['stream-block-limit'] = $this->getStreamBlockLimit();
        $config['push-notification-limit'] = config('alarm.notification.limit');
        $config['locale'] = $school->locale;
        $config['aruba-coords-enabled'] = $this->getArubaCoordinatesStatus();
        $config['google-maps-enabled'] = config('google.maps.enabled');
        $config['google-zoom-level'] = config('google.maps.zoom_level');
        $config['cisco-enabled'] = config('cisco.coors.enabled');
        $config['use-gps'] = $school->is_gps_location_source ? true : false;
        $config['use-non-gps'] = $school->is_non_gps_location_source ? true : false;

        return $config;
    }
}
