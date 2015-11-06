<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System\Schools;

use BComeSafe\Http\Controllers\System\BaseController;
use BComeSafe\Http\Requests;
use BComeSafe\Models\CrisisTeamMember;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;
use Illuminate\Http\Request;

class MainController extends BaseController
{
    public function getIndex()
    {
        return view('system.schools.index', ['shelterId' => config('alarm.default_id')]);
    }

    public function getList()
    {
        return School::orderBy('name', 'asc')->get();
    }

    public function postSaveSchool(Request $request)
    {
        $data = $request->only(['id', 'ip_address', 'mac_address', 'ad_id', 'phone_system_id', 'campus_id', 'name']);

        if ($data['id']) {
            $item = School::find($data['id']);

            $adId = $item->ad_id;
            $item->update($data);

            //If active directory group id has changed
            //then update the crisis team
            if ($adId !== $data['ad_id']) {
                CrisisTeamMember::sync($data['id']);
            }
        }

        return $item;
    }

    private function getPhoneSystemIdList()
    {
        // Get integration
        $default = SchoolDefault::getDefaults();
        $integration = \Component::get('PhoneSystem')
            ->getIntegration($default->phone_system_provider);

        return $integration->getNodes();
    }

    public function postPhoneSystemIdValidate(Request $request)
    {
        $nodeId = $request->get('nodeId');
        $nodes = $this->getPhoneSystemIdList();

        foreach ($nodes as $node) {
            if ($nodeId === $node['id']) {
                return response()->json('true');
            }
        }

        return \Lang::get('validation.valid_phone_system_id');
    }


    public function postValidateIp(Request $request)
    {
        $data = $request->only(['id', 'ip']);
        if (filter_var($data['ip'], FILTER_VALIDATE_IP) === false) {
            return \Lang::get('validation.valid_ip');
        } else {
            $model=School::where('ip_address', '=', $data['ip'])->first();
            if (!is_null($model) && $model->id != $data['id']) {
                return \Lang::get('validation.ip_in_use');
            } elseif (!is_null($model) && $model->id == $data['id']) {
                return 'true';
            }
        }
    }
}
