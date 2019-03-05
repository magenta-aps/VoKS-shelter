<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\EventLog;
use Illuminate\Http\Request;

class ReportsController extends BaseController
{
    public function getIndex()
    {
        return view('admin.reports.index');
    }

    public function getList()
    {
        $list = EventLog::where('school_id', '=', \Shelter::getID())->orderBy('created_at', 'desc')->get();

        for ($i=0; $i<count($list); $i++) {
            switch ($list[$i]->log_type) {
                case "alarm_triggered":
                    $log_type = \Lang::get('admin.reports.table.log_types.alarm_triggered');
                    break;
                default:
                    $log_type = \Lang::get('admin.reports.table.log_types.unknown_type');
            }
            $list[$i]->log_type = $log_type;
        }
        return $list;
    }



    public function postRemoveLogItem(Request $request)
    {
        $item = EventLog::find($request->get('id'));

        if ($item) {
            $item->delete();
        }
        return $item;
    }

}
