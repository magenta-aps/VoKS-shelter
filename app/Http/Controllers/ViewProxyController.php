<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers;

use BComeSafe\Models\School;
use BComeSafe\Models\SchoolDefault;

class ViewProxyController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin.access');
    }

    public function getIndex($partial)
    {
        $data = [];

        // placed in views/shelter/views
        $namespace = 'shelter.views.';

        // need to replace .html with -html because
        $name = str_replace('.', '-', $partial);
        $partial = $namespace . $name;

        // preload data for audio tab
        if ('map-html' === $name) {
            // Get integration
            $defaults = SchoolDefault::getDefaults();

	        $integration = null;

            if ( $defaults->phone_system_provider )
            {
	            $integration = \Component::get('PhoneSystem')
	                                     ->getIntegration($defaults->phone_system_provider);
            }


            // Get node identifier
            $schoolId = \Shelter::getID();
            $school = School::getSettings($schoolId);
            $nodeId = $school->phone_system_id;

            $data = [
                'audio' => [
                    'voices' => $integration ? $integration->getVoices($nodeId) : null,
                    'groups' => $integration ? $integration->getGroups($nodeId) : null,
                    'number' => $school->phone_system_number
                ]
            ];
        }
        
        if (view()->exists($partial)) {
            return view($partial, $data);
        }

        return abort(404);
    }
}
