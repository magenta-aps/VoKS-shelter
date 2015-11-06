<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\School;
use Illuminate\Http\Request;

class GeneralController extends BaseController
{
    public function getIndex()
    {

        return view(
            'admin.general.index',
            [
            'school' => School::getSettings(),
            'timezones' => get_timezone_list(),
            'languages' => get_available_languages(),
            'orderingOptions' => get_sorting_options(),
            ]
        );
    }

    public function postSave(Request $request)
    {
        $data = $request->only(['timezone', 'locale', 'ordering']);

        School::findAndUpdate(['id' => \Shelter::getID()], $data);

        return back();
    }
}
