<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\EventReport;
use Illuminate\Http\Request;

class ReportsController extends BaseController
{
    public function getIndex()
    {
        return view('admin.reports.index');
    }

    public function getList()
    {
        $list = EventReport::where('school_id', '=', \Shelter::getID())->orderBy('created_at', 'desc')->get();
        for ($i=0; $i<count($list); $i++) {
            $list[$i]->duration = gmdate("H:i:s", $list[$i]->duration);
            $list[$i]->log_download_link = $this->makeLink($list[$i], 'csv');
            $list[$i]->report_download_link = $this->makeLink($list[$i], 'pdf');
        }
        return $list;
    }

    private function makeLink($reportItem, $linkType) {

        return "/admin/reports/download?type=$linkType&school={$reportItem['school_id']}&alarm_time={$reportItem['triggered_at']}";
    }
}
