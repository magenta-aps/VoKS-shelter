<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Api;

use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Http\Requests\BcsRequest;
use BComeSafe\Models\School;

/**
 * Class BcsController
 *
 * @package BComeSafe\Http\Controllers\Api
 */
class BcsController extends Controller
{
    /**
     * @param \BComeSafe\Http\Requests\BcsRequest $request
     *
     * @return json
     */
    public function anyList(BcsRequest $request)
    {
        $ret_val = array();
        $list = School::where('display', '=', '1')->toArray();
        if (empty($list)) {
          return response()->json($ret_val);
        }

        foreach ($list as $s) {
          $ret_val[] = array_map_keys(
            $s,
            [
              'bcs_id'               => 'id',
              'bcs_name'             => 'name',
              'bcs_url'              => 'url',
              'police_number'        => 'police_number',
              'display'              => 'display'
            ]
          );
        }

        return response()->json($ret_val);
    }
}
