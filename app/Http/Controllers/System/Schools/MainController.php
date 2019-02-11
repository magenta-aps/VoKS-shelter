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
use BComeSafe\Libraries\SchoolIpHandler;
use BComeSafe\Models\CrisisTeamMember;
use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Packages\Websocket\ShelterClient;
use Illuminate\Http\Request;

/**
 * Class MainController
 *
 * @package  BComeSafe\Http\Controllers\System\Schools
 */
class MainController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('system.schools.index', ['shelterId' => config('alarm.default_id')]);
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return School::orderBy('name', 'asc')->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection|null|static
     */
    public function postSaveSchool(Request $request)
    {
        $data = $request->only(['id', 'ip_address', 'mac_address', 'ad_id', 'phone_system_id', 'campus_id', 'name']);

        $item = School::find($data['id']);

        $adId = $item->ad_id;
        $ipAddress = $item->ip_address;

        $item->update($data);

        // if active directory group id has changed
        // then update the crisis team
        if ($adId !== $data['ad_id']) {
            CrisisTeamMember::sync($data['id']);
        }

        if ($ipAddress !== $data['ip_address']) {
            $ipHandler = new SchoolIpHandler();
            try {
                $client = new ShelterClient(config('alarm.php_ws_url') . '/' . config('alarm.php_ws_client'));
                $client->updateIpWhitelist($ipHandler->getMappedList());
            } catch(\Exception $e) {

            }
        }

        return $item;
    }

    /**
     * @return mixed
     */
    private function getPhoneSystemIdList()
    {
        // Get integration
        $default = SchoolDefault::getDefaults();
        $integration = \Component::get('PhoneSystem')
            ->getIntegration($default->phone_system_provider);

        return $integration->getNodes();
    }

    /**
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
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


    /**
     * @param Request $request
     * @return array
     */
    public function postValidateIp(Request $request)
    {
        $ip = new SchoolIpHandler();
        return $ip->validateUniqueness($request->get('ip'), $request->get('id'));
    }
}
