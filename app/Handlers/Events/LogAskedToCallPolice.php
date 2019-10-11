<?php

namespace BComeSafe\Handlers\Events;


use BComeSafe\Events\AskedToCallPolice;
use BComeSafe\Models\EventLog;
use BComeSafe\Models\School;
use BComeSafe\Models\Device;

class LogAskedToCallPolice
{
    protected $school;
    protected $device;

    public function handle(AskedToCallPolice $event)
    {
        $this->school = School::getSettings($event->schoolId);
        if (isset($event->deviceId)) {
            $this->device = Device::getByDeviceId($event->deviceId);
        }

        $data = [
            'log_type' => EventLog::ASKED_TO_CALL_POLICE,
            'school_id' => $event->schoolId,
        ];
        if ($this->device) {
            $data['device_type'] = $this->device['device_type'];
            $data['device_id'] = $event->deviceId;
            $data['fullname'] = $this->device['fullname'];
            $data['mac_address'] = $this->device['mac_address'];
            $data['floor_id'] = $this->device['floor_id'];
            $data['x'] = $this->device['x'];
            $data['y'] = $this->device['y'];
        } else {
            $data['device_type'] = 'shelter';
        }
        EventLog::create($data);
    }
}