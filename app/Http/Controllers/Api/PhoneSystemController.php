<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Api;

use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Models\History;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;
use Illuminate\Http\Request;

/**
 * Phone System Controller
 * API endpoint
 */
class PhoneSystemController extends Controller
{
    /**
     * Default configuration
     *
     * @var SchoolDefault
     */
    public $defaults;

    /**
     * Phone system integration
     *
     * @var \BComeSafe\Packages\PhoneSystem\Contracts\IntegrationContract
     */
    public $integration;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->defaults = SchoolDefault::getDefaults();
        $this->integration = \Component::get('PhoneSystem')
            ->getIntegration($this->defaults->phone_system_provider);
    }

    /**
     * GET request returns a list of all available phone system nodes
     *
     * @link /api/ps/nodes
     *
     * @param Request $request
     *
     * @return string JSON encoded response
     */
    public function nodes(Request $request)
    {
        $list = $this->integration->getNodes();
        return response()->json($list);
    }

    /**
     * GET request fetches voice recordings and device groups.
     * POST request calls play API method with selected media and group identifiers.
     *
     * @link /api/ps/play
     *
     * @param Request $request
     *
     * @return string JSON encoded response
     */
    public function play(Request $request)
    {
        // Get school settings
        $shelterId = $request->input('shelterId');
        $settings = School::getSettings($shelterId);

        // Get node identifier
        $nodeId = $settings->phone_system_id;

        // Get voices and groups
        $voices = $this->integration->getVoices($nodeId);
        $groups = $this->integration->getGroups($nodeId);

        // POST
        if (true === $request->isMethod('post')) {
            $success = false;

            // Setup validation
            $input = $request->only(['voiceId', 'groupId']);
            $rules = [
                'voiceId' => 'required|integer|in:' . implode(',', array_keys($voices)),
                'groupId' => 'required|integer|in:' . implode(',', array_keys($groups))
            ];

            // Validate inputs
            $validator = \Validator::make($input, $rules);
            if ($validator->passes()) {
                $success = $this->integration->play($nodeId, $input['voiceId'], $input['groupId'], 0, 0, 0);

                // History message
                History::create(
                    [
                    'type' => 'play',
                    'message' => 'shelter/history.audio.play.message',
                    'result' => [
                        'voice' => $voices[$input['voiceId']],
                        'group' => $groups[$input['groupId']],
                        'interrupt' => null,
                        'status' => $success
                    ]
                    ]
                );
            }

            return response()->json(['success' => $success]);
        }

        // Prepare voices and groups arrays for JSON
        // This is needed as our version of ui-select does not support
        // iterating over objects.
        if (!empty($voices)) {
            $voicesJson = [];
            foreach ($voices as $id => $name) {
                $voicesJson[] = [
                    'id' => $id,
                    'name' => $name
                ];
            }
        }

        if (!empty($groups)) {
            $groupsJson = [];
            foreach ($groups as $id => $name) {
                $groupsJson[] = [
                    'id' => $id,
                    'name' => $name
                ];
            }
        }

        // GET
        return response()->json(
            [
            'voices' => $voicesJson,
            'groups' => $groupsJson
            ]
        );
    }

    /**
     * GET fetches device groups and phone number
     * POST makes a call to provided phone number to record new voice message
     * and plays it to the group members.
     *
     * @link /api/ps/broadcast
     *
     * @param Request $request Request information
     *
     * @return string JSON encoded response
     */
    public function broadcast(Request $request)
    {
        // Get school settings
        $shelterId = $request->input('shelterId');
        $settings = School::getSettings($shelterId);

        // Get node identifier
        $nodeId = $settings->phone_system_id;

        // Get groups
        $groups = $this->integration->getGroups($nodeId);

        // POST
        if (true === $request->isMethod('post')) {
            $success = false;

            // Setup validation
            $input = $request->only(['number', 'groupId']);
            $rules = [
                'number' => 'required|string',
                'groupId' => 'required|integer|in:' . implode(',', array_keys($groups))
            ];

            // Validate inputs
            $validator = \Validator::make($input, $rules);
            if ($validator->passes()) {
                $success = $this->integration->broadcast($nodeId, $input['number'], $input['groupId'], 0, 0, true);

                // History message
                History::create(
                    [
                    'type' => 'live',
                    'message' => 'shelter/history.audio.live.message',
                    'result' => [
                        'number' => $input['number'],
                        'group' => $groups[$input['groupId']],
                        'status' => $success
                    ]
                    ]
                );
            }

            return response()->json(['success' => $success]);
        }

        // Prepare groups array for JSON
        // This is needed as our version of ui-select does not support
        // iterating over objects.
        if (!empty($groups)) {
            $groupsJson = [];
            foreach ($groups as $id => $name) {
                $groupsJson[] = [
                    'id' => $id,
                    'name' => $name
                ];
            }
        }

        // GET
        $number = $settings->phone_system_number;
        return response()->json(
            [
            'number' => $number,
            'groups' => $groupsJson
            ]
        );
    }
}
