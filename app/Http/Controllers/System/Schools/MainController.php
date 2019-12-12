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
	    $default = SchoolDefault::getDefaults();

	    $data = [
        'shelterId'           => School::getDefaultSchoolID(),
        'use_gps'             => $default->is_gps_location_source,
        'use_non_gps'         => $default->is_non_gps_location_source,
        'phone_provider'      => $default->phone_system_provider ? true : false,
        'ad_enabled'          => config('ad.enabled'),
        'controllers_enabled' => config('aruba.controllers.enabled'),
	    ];

        return view('system.schools.index', $data);
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
        $data = $request->only([
          'id', 
          'ip_address', 
          'mac_address', 
          'ad_id', 
          'phone_system_id', 
          'campus_id',
          'controller_url',
          'controller_version',
          'name',
          'url',
          'police_number',
          'use_gps',
          'display',
          'public'
        ]);

	    if ( isset($data['id']) )
        {
	        $item = School::find($data['id']);

	        $adId = $item->ad_id;
	        $ipAddress = $item->ip_address;

	        $item->update($data);
        }
        else
        {
        	$defaults = SchoolDefault::getDefaults();

        	$data['ordering'] = isset($data['ordering']) ? $data['ordering'] : $defaults->ordering;
        	$data['locale'] = isset($data['locale']) ? $data['locale'] : $defaults->locale;
        	$data['timezone'] = isset($data['timezone']) ? $data['timezone'] : $defaults->timezone;
        	$data['campus_id'] = isset($data['campus_id']) ? $data['campus_id'] : 0;
        	$data['url'] = isset($data['url']) ? $data['url'] : '';
        	$data['police_number'] = isset($data['police_number']) ? $data['police_number'] : '';
        	$data['use_gps'] = isset($data['use_gps']) ? $data['use_gps'] : 0;
        	$data['display'] = isset($data['display']) ? $data['display'] : 0;
        	$data['public'] = isset($data['public']) ? $data['public'] : 1;
        	$data['controller_url'] = isset($data['controller_url']) ? $data['controller_url'] : '';
        	$data['controller_version'] = isset($data['controller_version']) ? $data['controller_version'] : '';

        	$item = School::create($data);

	        $adId = isset($data['ad_id']) ? $data['ad_id'] : null;
	        $ipAddress = isset($data['ip_address']) ? $data['ip_address'] : null;
        }

        // if active directory group id has changed
        // then update the crisis team
        if (isset($data['ad_id']) && !empty($data['ad_id']) && $adId !== $data['ad_id']) {
            CrisisTeamMember::sync($data['id']);
        }

        if ($ipAddress !== $data['ip_address']) {
            $ipHandler = new SchoolIpHandler();
            try {
                $client = new ShelterClient(config('alarm.php_ws_url') . '/' . config('alarm.php_ws_client'));
                $client->updateIpWhitelist($ipHandler->getMappedList());
            } catch (\Exception $e) {

            }
        }

        return $item;
    }

	/**
	 * @param Request $request
	 * @return \Illuminate\Support\Collection|null|static
	 */
	public function postRemoveSchool(Request $request)
	{
		$id = $request->get('id');

		if ( $school = School::find($id) )
		{
			$school->delete();

			return response()->json(true);
		}

		return response()->json(false);
	}

	/**
     * @return mixed
     */
    private function getPhoneSystemIdList()
    {
        // Get integration
        $default = SchoolDefault::getDefaults();

        if ( !$default->phone_system_provider )
        {
        	return [];
        }

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

        if (empty($nodes)) {
	        return response()->json('true');
        }

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
