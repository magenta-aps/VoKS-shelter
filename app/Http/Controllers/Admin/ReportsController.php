<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\EventReport;
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
        $list = EventReport::where('school_id', '=', \Shelter::getID())->orderBy('created_at', 'desc')->get();
        for ($i=0; $i<count($list); $i++) {
            $list[$i]->duration = gmdate("H:i:s", $list[$i]->duration);
            $list[$i]->log_download_link = $this->makeLink($list[$i], 'csv');
            $list[$i]->report_download_link = $this->makeLink($list[$i], 'pdf');
            $data = json_decode($list[$i]->data, true);
            $list[$i]->false_alarm = isset($data['false_alarm']) ? $data['false_alarm'] : 0;
            $list[$i]->note = isset($data['note']) ? $data['note'] : "";
        }
        return $list;
    }

    public function postSaveReportItem(Request $request) {
        $data = $request->only([
            'id',
            'false_alarm',
            'note'
        ]);
        $formattedData = array();
        $formattedData['id'] = $data['id'];
        $formattedData['data'] = array('false_alarm' => $data['false_alarm'], 'note' => $data['note']);
        // NOTE: We are updating event_logs source table
        $item = EventLog::find($data['id']);
        $initialArray = json_decode($item->data, true);
        $formattedData['data'] = json_encode($formattedData['data'] + $initialArray);
        $item->update($formattedData);
    }

    private function makeLink($reportItem, $linkType) {

        return "/admin/reports/download?type=$linkType&school={$reportItem['school_id']}&alarm_time={$reportItem['triggered_at']}";
    }
}
