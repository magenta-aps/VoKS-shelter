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
        $schoolId = \Shelter::getID();
        $list = EventReport::where('school_id', '=', $schoolId)->orderBy('created_at', 'desc')->get();
        for ($i=0; $i<count($list); $i++) {
            $list[$i]->duration = gmdate("H:i:s", $list[$i]->duration);
            $list[$i]->log_download_link = $this->makeLink($list[$i], 'csv');
            $list[$i]->report_download_link = $this->makeLink($list[$i], 'pdf');
            $eventLogData = $this->getEventLogData($schoolId, $list[$i]->triggered_at);
            if (!$eventLogData) continue;
            $list[$i]->fullname = $eventLogData['fullname'];
            $list[$i]->device_id = $eventLogData['device_id'];
            $list[$i]->device_type = $eventLogData['device_type'];
            $list[$i]->push_notifications = $eventLogData['push_notifications_sent'];
            $list[$i]->video_chats = $eventLogData['video_chats'];
            $list[$i]->audio_played = $eventLogData['audio_played'];
            $list[$i]->other_events = $eventLogData['other_events'];
        }
        return $list;
    }

    public function postSaveReportItem(Request $request) {
        $data = $request->only([
            'id',
            'false_alarm',
            'note'
        ]);
        return EventReport::findAndUpdate(['id' => $data['id']], $data);
    }

    public function postRemoveReportItem(Request $request)
    {
        $item = EventReport::find($request->get('id'));
        if ($item) {
            $item->delete();
        }

        return $item;
    }

    private function makeLink($reportItem, $linkType) {

        return "/admin/reports/download?type=$linkType&school={$reportItem['school_id']}&alarm_time={$reportItem['triggered_at']}";
    }

    private function getEventLogData($schoolId, $triggeredAt) {
        $eventLogs = EventLog::where(['school_id' => $schoolId, 'triggered_at' => $triggeredAt])->get();
        if (count($eventLogs) == 0) return null;

        $data = ['push_notifications_sent' => 0, 'video_chats' => 0, 'audio_played' => 0, 'other_events' => 0];
        for ($i=0; $i<count($eventLogs); $i++) {
            switch($eventLogs[$i]->log_type) {
                case "push_notification_sent":
                    $data['push_notifications_sent']++;
                    break;
                case "audio_played":
                    $data['audio_played']++;
                    break;
                case "video_chat_started":
                    $data['video_chats']++;
                    break;
                case "alarm_triggered":
                    $data['device_id'] = $eventLogs[$i]->device_id;
                    $data['device_type'] = $eventLogs[$i]->device_type;
                    $data['fullname'] = $eventLogs[$i]->fullname;
                    break;
                default:
                    $data['other_events']++;
            }
        }
        return $data;
    }
}
