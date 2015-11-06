<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\CrisisTeamMember;

/**
 * Class CrisisTeamController
 *
 * @package BComeSafe\Http\Controllers\Admin
 */
class CrisisTeamController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view(
            'admin.crisis-team.index',
            [
            'list' => CrisisTeamMember::where('school_id', '=', \Shelter::getID())->get()
            ]
        );
    }

    /**
     * @return array
     */
    public function getSync()
    {
        $status = CrisisTeamMember::sync(\Shelter::getID());
        if (empty($status)) {
            return array();
        }

        return [
            'success' => true
        ];
    }
}
