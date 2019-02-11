<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\SmsMessage;
use BComeSafe\Models\SmsMessageDefault;
use Illuminate\Http\Request;

/**
 * Class SmsController
 *
 * @package BComeSafe\Http\Controllers\Admin
 */
class SmsController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $default = SmsMessageDefault::first();
        if (!$default) {
            $default = SmsMessageDefault::firstOrNew(
                [
                'initial_message' => '',
                'crisis_team_message' => '',
                ]
            );
        }

        $sms = SmsMessage::where('school_id', '=', \Shelter::getID())->first();
        if (!$sms) {
            $sms = SmsMessage::firstOrNew(
                [
                'initial_message' => '',
                'crisis_team_message' => '',
                ]
            );
        }

        return view(
            'admin.sms.index',
            [
            'sms' => $sms,
            'default' => $default
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSaveSms(Request $request)
    {
        $data = $request->only(
            [
            'id',
            'initial_message',
            'crisis_team_message',
            ]
        );

        $id = $data['id'];

        $data['school_id'] = \Shelter::getID();
        unset($data['id']);
        SmsMessage::findAndUpdate(['id' => $id], $data);

        return back();
    }
}
