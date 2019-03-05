<?php
/**
 * Created by IntelliJ IDEA.
 * User: emt
 * Date: 3/1/19
 * Time: 9:40 AM
 */

namespace BComeSafe\Handlers\Events;


use BComeSafe\Events\AlarmWasTriggered;
use BComeSafe\Models\EventLog;
use BComeSafe\Models\School;
use BComeSafe\Models\Device;

class LogAlarmTrigger
{
    protected $school;
    protected $device;

    public function handle(AlarmWasTriggered $event)
    {
        \Log::debug("HANDLING EVENT ###############################");
        $this->school = School::getSettings($event->schoolId);
        if (isset($event->deviceId)) {
            $this->device = Device::getByDeviceId($event->deviceId);
        }

        $data = [
            'log_type' => 'alarm_triggered',
            'school_id' => $event->schoolId,
        ];
        if ($this->device) {
            $data['device_type'] = $this->device['device_type'];
            $data['device_id'] = $event->deviceId;
            $data['fullname'] = $this->device['fullname'];
            $data['mac_address'] = $this->device['mac_address'];
            $data['floor_id'] = $this->device['floor_id'];
        } else {
            $data['device_type'] = 'shelter';
        }
        $data['data'] = 'sample_data';
        EventLog::create($data);
    }
}