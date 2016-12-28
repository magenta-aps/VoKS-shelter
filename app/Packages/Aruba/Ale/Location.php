<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Aruba\Ale;

use BComeSafe\Libraries\CurlRequest;

class Location
{
    const COORDINATES_UNAVAILABLE = 1;
    const COORDINATES_NOT_MAPPED  = 2;

    /**
     * Fetches coordinates for a MAC address from ALE
     *
     * @param  $macAddress
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getCoordinates($macAddress)
    {
        return static::pullLocations(
            $macAddress,
            function ($data) use ($macAddress) {
                $position = array_get($data, 'Location_result.0.msg');
                if (empty($position)) {
                    return [
                        'x'         => 0,
                        'y'         => 0,
                        'floor_id'  => '',
                        'campus_id' => ''
                    ];
                }

                $profile = static::mapKeys($position);
                $profile['mac_address'] = format_mac_address($macAddress);

                return $profile;
            }
        );
    }


    /**
     * Fetches coordinates for all available devices from ALE
     *
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getAllCoordinates()
    {
        return static::pullLocations(
            null,
            function ($data) {
                $positions = array_get($data, 'Location_result');
                if (empty($positions)) {
                    return [];
                }

                $count = count($positions);
                $locations = [];

                for ($i = 0; $i < $count; $i++) {
                    $location = static::mapKeys($positions[$i]['msg']);
                    $location['mac_address'] = format_mac_address($positions[$i]['msg']['sta_eth_mac']['addr']);

                    $locations[] = $location;
                }

                return $locations;
            }
        );
    }

    /**
     * Fetches coordinates for either one or all clients
     * with a response formatter callback
     *
     * @param          $macAddress
     * @param \Closure $callback
     *
     * @return array
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function pullLocations($macAddress = null, \Closure $callback = null)
    {
        if (config('aruba.ale.enabled') === false) {
            return [
                'x'         => 0,
                'y'         => 0,
                'floor_id'  => '',
                'campus_id' => ''
            ];
        }

        $parameters = [];

        if (null !== $macAddress) {
            $parameters['sta_eth_mac'] = $macAddress;
        }

        $curl = new CurlRequest();
        $curl->setUrl(
            'https://' .
            config('aruba.ale.baseUrl') .
            config('aruba.ale.apiUrl') . '/location',
            $parameters
        );

        $curl->setAuthentication(config('aruba.ale.username'), config('aruba.ale.password'));
        $curl->expect(CurlRequest::JSON_RESPONSE, $callback);

        $response = $curl->execute();

        return $response;
    }

    /**
     * Maps location properties from Aruba's structure into ours
     *
     * @param  $position
     * @return array
     */
    public static function mapKeys($position)
    {
        return array_map_keys(
            $position,
            [
                'x'         => 'sta_location_x',
                'y'         => 'sta_location_y',
                'floor_id'  => 'floor_id',
                'campus_id' => 'campus_id'
            ]
        );
    }

    /**
     * Fetches a list of MAC addresses that are currently
     * connected Aruba clients
     *
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getStations()
    {
        if (config('aruba.ale.enabled') === false) {
            return [];
        }

        $curl = new CurlRequest();
        $curl->setUrl(
            'https://' .
            config('aruba.ale.baseUrl') .
            config('aruba.ale.apiUrl') . '/station'
        );

        $curl->setAuthentication(config('aruba.ale.username'), config('aruba.ale.password'));

        $curl->expect(
            CurlRequest::JSON_RESPONSE,
            function ($data) {
                if (empty($data)) {
                    return [];
                }

                $stations = [];
                foreach ($data['Station_result'] as $station) {
                    $stations[] = format_mac_address($station['msg']['sta_eth_mac']['addr']);
                }

                return $stations;
            }
        );

        $response = $curl->execute();

        return $response;
    }

    /**
     * Fetches a list of floors from ALE and maps with Airwave
     * floor IDs based on floor image paths
     *
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getFloors()
    {
        if (config('aruba.ale.enabled') === false) {
            return [];
        }

        $curl = new CurlRequest();
        $curl->setUrl(
            'https://' .
            config('aruba.ale.baseUrl') .
            config('aruba.ale.apiUrl') . '/floor'
        );

        $curl->setAuthentication(config('aruba.ale.username'), config('aruba.ale.password'));

        $curl->expect(
            CurlRequest::JSON_RESPONSE,
            function ($data) {
                if (empty($data)) {
                    return [];
                }

                $floors = [];
                foreach ($data['Floor_result'] as $floor) {
                    $floorId = $floor['msg']['floor_id'];

                    // image path has the old airwave floor id in the path
                    // we'll extract and use it to map the ALE floor_id to the airwave floor_id
                    preg_match('/images\/(.*)\.jpg/', $floor['msg']['floor_img_path'], $matches);

                    if (isset($matches[1])) {
                        $floors[$matches[1]] = $floorId;
                    }
                }

                return $floors;
            }
        );

        $response = $curl->execute();

        return $response;
    }
}
