<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Models;

use BComeSafe\Models\SchoolDefaultFields;

/**
 * Class SchoolDefault
 *
 * @package BComeSafe\Models
 */
class SchoolDefault extends BaseModel
{

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'timezone',
        'locale',
        'ordering',
        'sms_provider',
        'phone_system_provider',
        'user_data_source',
        'client_data_source'
    ];

	/**
	 * @var SchoolDefault
	 */
    protected static $cached_defaults = null;

    /**
     * @var array
     */
    protected $guarded = ['_token'];

	/**
	 * @var array
	 */
    protected $appends = [
    	'is_gps_location_source',
    	'is_non_gps_location_source',
    ];

    /**
     * @return SchoolDefault
     */
    public static function getDefaults()
    {
    	if ( self::$cached_defaults )
	    {
	    	return self::$cached_defaults;
	    }

        $defaults = static::first();
        if (!$defaults) {
            $defaults = static::firstOrNew(
                [
                'ordering' => config('sorting.default'),
                'timezone' => 'Europe/Vilnius',
                'locale' => 'en',
                'sms_provider' => 'Ucp',
                'phone_system_provider' => 'Ucp'
                ]
            );
            $defaults->setAttribute('id', 0);
        }

	    self::$cached_defaults = $defaults;

        return self::$cached_defaults;
    }

	/**
	 * Checks if user data location sources are not the ones provided
	 *
	 * @param string|array $sources
	 *
	 * @return bool
	 */
	public function hasNotLocationSource( $sources )
    {
    	if ( !isset($this->client_data_source) || empty($this->client_data_source) )
	    {
	    	return true;
	    }

	    $sources = is_array($sources) ? $sources : func_get_args();

	    return !in_array( $this->client_data_source, $sources );
    }

	/**
	 * Checks if user data location sources are the ones provided
	 *
	 * @param string|array $sources
	 *
	 * @return bool
	 */
	public function hasLocationSource( $sources )
	{
		if ( !isset($this->client_data_source) || empty($this->client_data_source) )
		{
			return true;
		}

		$sources = is_array($sources) ? $sources : func_get_args();

		return in_array( $this->client_data_source, $sources );
	}

	/**
	 * Checks if location source is provided by GPS
	 * @return bool
	 */
    public function getIsGpsLocationSourceAttribute()
    {
	    if ( !isset($this->client_data_source) || empty($this->client_data_source) )
	    {
		    return false;
	    }

	    return $this->hasNotLocationSource( SchoolDefaultFields::DEVICE_LOCATION_SOURCE_ALE, SchoolDefaultFields::DEVICE_LOCATION_SOURCE_CISCO );
    }

	/**
	 * Checks if location source is provided by GPS
	 * @return bool
	 */
	public function getIsNonGpsLocationSourceAttribute()
	{
		if ( !isset($this->client_data_source) || empty($this->client_data_source) )
		{
			return false;
		}

		return $this->hasLocationSource( SchoolDefaultFields::DEVICE_LOCATION_SOURCE_GOOGLE );
	}
}
