<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;
use Illuminate\Http\Request;

class PhoneSystemController extends BaseController
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
     * Shelter identifier
     *
     * @var integer
     */
    public $shelterlId;

    /**
     * School settings
     *
     * @var stdClass object
     */
    public $school;

    /**
     * Node identifier
     *
     * @var integer
     */
    public $nodeId;

    /**
     * Phone system
     *
     * @var \BComeSafe\Packages\PhoneSystem\Contracts\IntegrationContract
     */
    public $system;

    /**
     * Phone system group list
     *
     * @var array
     */
    public $groups;

    /**
     * Phone system media list
     *
     * @var array
     */
    public $media;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Get integration
        $this->defaults = SchoolDefault::getDefaults();
        $this->system = \Component::get('PhoneSystem')
            ->getIntegration($this->defaults->phone_system_provider);

        // Get school settings and assigned phone system
        $this->shelterId = \Shelter::getID();
        $this->school = School::getSettings($this->shelterId);
        $this->nodeId = $this->school->phone_system_id;

        // Get available groups and media, used for displaying and validation
        $this->groups = $this->system->getGroups($this->nodeId);
        if (!is_array($this->groups)) {
            $this->groups = [];
        }

        $this->media = $this->system->getVoices($this->nodeId);
        if (!is_array($this->media)) {
            $this->media = [];
        }
    }

    /**
     * Index
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        // Get current group id and group list
        $groupId = $this->school->phone_system_group_id;
        $groups = $this->groups;
        if (!$groupId) {
            $groups = array('' => \Lang::get('admin/phone.alarm.field.group.value')) + $groups;
        }

        // Get current media id and media list
        $mediaId = $this->school->phone_system_voice_id;
        $media = $this->media;
        if (!$mediaId) {
            $media = array('' => \Lang::get('admin/phone.alarm.field.media.value')) + $media;
        }

        // Get interrupt media identifier
        $interruptId = $this->school->phone_system_interrupt_id;
        $interrupt = array('0' => \Lang::get('admin/phone.alarm.field.interrupt.value')) + $this->media;

        // Alarm trigger section
        $alarm = [
            'media_id' => $mediaId,
            'group_id' => $groupId,
            'interrupt_id' => $interruptId,

            'groups' => $groups,
            'media' => $media,
            'interrupt' => $interrupt
        ];

        // Live broadcast section
        $broadcast = [
            'number' => $this->school->phone_system_number
        ];

        return view(
            'admin.phone.index',
            [
            'alarm' => $alarm,
            'broadcast' => $broadcast
            ]
        );
    }

    /**
     * Save Alarm trigger settings
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSaveAlarm(Request $request)
    {
        // Field list to validate
        $data = [
            'phone_system_group_id' => $request->get('phone_system_group_id'),
            'phone_system_voice_id' => $request->get('phone_system_media_id'),
            'phone_system_interrupt_id' => $request->get('phone_system_interrupt_id')
        ];

        // Existing values as string to be used with "in"
        // http://laravel.com/docs/5.0/validation#rule-in
        $groups = implode(',', array_keys($this->groups));
        $media = implode(',', array_keys($this->media));
        $interrupt = '0,' . implode(',', array_keys($this->media));

        // Validation rules
        $rules = [
            'phone_system_group_id' => [
                'required',
                'integer',
                'in:' . $groups
            ],
            'phone_system_voice_id' => [
                'required',
                'integer',
                'in:' . $media
            ],
            'phone_system_interrupt_id' => [
                'required',
                'integer',
                'in:' . $interrupt
            ]
        ];

        // Validator & default return
        $validator = \Validator::make($data, $rules);
        $return = back();

        // Error: display
        if ($validator->fails()) {
            return $return
                ->withErrors($validator)
                ->withInput();
        }

        // Success: save
        School::findAndUpdate(['id' => $this->shelterId], $data);
        return $return;
    }

    /**
     * Save live broadcast settings
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSaveBroadcast(Request $request)
    {
        // Field list to validate
        $data = [
            'phone_system_number' => $request->get('phone_system_number')
        ];
        
        // Validation rules
        $rules = [
            'phone_system_number' => [
                'required'
            ]
        ];

        // Validator & default return
        $validator = \Validator::make($data, $rules);
        $return = back();

        // Error: display
        if ($validator->fails()) {
            return $return
                ->withErrors($validator)
                ->withInput();
        }

        // Success: save
        School::findAndUpdate(['id' => $this->shelterId], $data);
        return $return;
    }
}
