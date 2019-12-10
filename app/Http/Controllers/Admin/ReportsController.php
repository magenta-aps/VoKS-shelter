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

    public function postList(Request $request)
    {
        $filter = $request->all();
        $schoolId = \Shelter::getID();
        $query = EventReport::where('school_id', '=', $schoolId);

        $where = $this->getWhereArrayFromSearchFilter($filter);
        for ($i = 0; $i<count($where); $i++) {
            $query = $query->where($where[$i][0], $where[$i][1], $where[$i][2]);
        }

        $list = $query->orderBy('created_at', 'desc')->get();
        for ($i=0; $i<count($list); $i++) {
            $list[$i]->log_download_link = $this->makeLink($list[$i], 'csv');
            $list[$i]->report_download_link = $this->makeLink($list[$i], 'pdf');

            $eventLogData = $this->getEventLogData($schoolId, $list[$i]->triggered_at);
            if (!$eventLogData) continue;

            $list[$i]->duration = gmdate("H:i:s", (strtotime($eventLogData['alarm_reset_time']) - strtotime($list[$i]->triggered_at)));
            $list[$i]->fullname = $eventLogData['fullname'];
            $list[$i]->device_id = $eventLogData['device_id'];
            $list[$i]->device_type = $eventLogData['device_type'];
            $list[$i]->push_notifications = $eventLogData['stats'][EventLog::PUSH_NOTIFICATION_SENT];
            $list[$i]->video_chats = $eventLogData['stats'][EventLog::VIDEO_CHAT_STARTED];
            $list[$i]->audio_played = $eventLogData['stats'][EventLog::AUDIO_PLAYED];
            $list[$i]->other_events = $eventLogData['stats']['other_events'];
            $list[$i]->video_link = $eventLogData['video_link'];
        }
        return $list;
    }

    public function getDownload(Request $request)
    {
        $data = $request->only([
            'type',
            'school_id',
            'triggered_at']);
        switch ($data['type']) {
            case "csv":
                return $this->getCsvDownload($data['school_id'], $data['triggered_at']);
                break;
            default:
                return "Unsupported download format";
                break;
        }
    }

    private function getCsvDownload($school_id, $triggered_at)
    {

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=' . preg_replace('/[\s:]/', '_', $triggered_at) . '.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $list = EventLog::where('school_id', '=', $school_id)->where('triggered_at', '=', $triggered_at)->orderBy('id', 'desc')->get()->toArray();

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
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
            $triggered_at = $item['triggered_at'];
            $item->delete();
            // Also delete related event_logs. TODO find out whether this is desired behaviour
            EventLog::where('triggered_at', '=', $triggered_at)->delete();
        }

        return $item;
    }

    private function makeLink($reportItem, $linkType) {

        return "/admin/reports/download?type=$linkType&school_id={$reportItem['school_id']}&triggered_at={$reportItem['triggered_at']}";
    }

    private function getEventLogData($schoolId, $triggeredAt) {
        $eventLogs = EventLog::where(['school_id' => $schoolId, 'triggered_at' => $triggeredAt])->get();
        if (count($eventLogs) == 0) return null;

        $data = [
            'stats' => [
                EventLog::PUSH_NOTIFICATION_SENT => 0,
                EventLog::VIDEO_CHAT_STARTED => 0,
                EventLog::AUDIO_PLAYED => 0,
                'other_events' => 0
            ],
            'alarm_reset_time' => '',
            'video_link' => ''
        ];

        // Merge event log data into simple assoc. arr
        for ($i=0; $i<count($eventLogs); $i++) {
            switch($eventLogs[$i]->log_type) {
                // Counters
                case EventLog::PUSH_NOTIFICATION_SENT:
                case EventLog::AUDIO_PLAYED:
                case EventLog::VIDEO_CHAT_STARTED:
                    $data['stats'][$eventLogs[$i]->log_type]++;
                    break;
                case EventLog::ALARM_TRIGGERED:
                    $data['device_id'] = $eventLogs[$i]->device_id;
                    $data['device_type'] = $eventLogs[$i]->device_type;
                    $data['fullname'] = $eventLogs[$i]->fullname;
                    break;
                case EventLog::SHELTER_RESET:
                    $data['alarm_reset_time'] = $eventLogs[$i]->created_at;
                    break;
                case EventLog::VIDEO_RECORDED:
                    $videoData = json_decode($eventLogs[$i]->data, true);
                    $data['video_link'] = $videoData['filename'];
                    break;
                default:
                    $data['stats']['other_events']++;
            }
        }
        return $data;
    }

    /*
    Returns an array of where clause arrays in the form ['field', 'operator', 'value']
    */
    private function getWhereArrayFromSearchFilter($filter) {
        // Always filter by school_id
        $where = array(['school_id', '=', \Shelter::getID()]);

        // Date filters
        $date = $filter['date'];
        if (isset($date['startDate'])) {
            array_push($where, ['created_at', '>=', $date['startDate']]);
        }
        if (isset($date['endDate'])) {
            array_push($where, ['created_at', '<=', $date['endDate']]);
        }

        // False alarm filters
        $false_alarm_filter = $filter['false_alarm'];
        switch ($false_alarm_filter) {
            case "no_false":
                array_push($where, ['false_alarm', '=', 0]);
                break;
            case "only_false":
                array_push($where, ['false_alarm', '=', 1]);
                break;
            default:
        }

        return $where;
    }
}
