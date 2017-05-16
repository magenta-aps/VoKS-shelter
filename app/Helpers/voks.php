<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

function get_available_languages()
{
        $directories = \File::directories(base_path('resources/lang'));
        $languages = [];
    for ($i = 0; $i < count($directories); $i++) {
        $code = basename($directories[$i]);
        // $languages[$code] = config('languages.' . $code);
        $languages[$code] = \Lang::get('languages.' .$code);
    }

        return $languages;
}

function get_sorting_options()
{
    $options = config('sorting.options');
    foreach ($options as $key => $option) {
        $options[$key] = \Lang::get($option);
    }

    return $options;
}

function prepend_none_option( $options = [] )
{
	$options = collect($options);
	return collect(['' => trans('system.contents.defaults.none')] + $options->all());
}

function get_available_user_data_sources()
{
	$sources = collect();
	config('ad.enabled') ? $sources->put( 'ad', trans('system.contents.sources.ad') ) : null;

	return prepend_none_option($sources);
}

function get_available_data_sources()
{
	$sources = collect();
	config('aruba.ale.enabled') ? $sources->put( 'ale', trans('system.contents.sources.ale') ) : null;
	config('cisco.coors.enabled') ? $sources->put( 'cisco', trans('system.contents.sources.cisco') ) : null;
	config('google.maps.enabled') ? $sources->put( 'google', trans('system.contents.sources.google') ) : null;

	return prepend_none_option($sources);
}

function format_mac_address($macAddress)
{
    if (!str_contains($macAddress, [':'])) {
        return join(':', str_split(str_replace(['.', '-'], '', $macAddress), 2));
    }

    return $macAddress;
}

function get_shelter_urls($schoolId, $deviceId)
{
    $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : config('alarm.url');
    $domain = preg_replace('/(:\d+)/', '', $domain);

    //suffix secure connections
    $protocol = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') ? '' : 's';

    if (config('alarm.secure') || $protocol === 's') {
        $protocol = 's';
        $port = 9001;
    } else {
        $port = 9000;
    }

    $api = 'http' . $protocol . '://' . $domain . '/api/device/';
    $ws = 'ws' . $protocol . '://' . $domain . ':'.$port.'/' . $schoolId . '/' . $deviceId;

    return compact('api', 'ws');
}

function get_random_name()
{
    $name = array_rand(config('names.list'));

    return config('names.list')[$name];
}
