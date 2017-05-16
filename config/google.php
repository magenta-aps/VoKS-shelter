<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [
    'maps'    => [
    	'enabled'   	=> env( 'GOOGLE_MAPS_ENABLED', true ),
    	'key'  	        => env( 'GOOGLE_MAPS_KEY' ),
		'zoom_level'	=> env( 'GOOGLE_MAPS_DEFAULT_ZOOM', 15 ),
    ],
];
