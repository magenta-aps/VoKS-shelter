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
    public static function getCoordinates($macAddress, $serverNumber = NULL)
    {
        return static::pullLocations(
            $macAddress,
            $serverNumber,
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
    public static function getAllCoordinates($serverNumber = NULL)
    {
        return static::pullLocations(
            null,
            $serverNumber,
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
    public static function pullLocations($macAddress = null, $serverNumber = NULL, \Closure $callback = null)
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

        $response = static::getAllData('location', $serverNumber, $parameters, $callback);

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
    public static function getStations($other = FALSE, $serverNumber = NULL)
    {

        $parameters = [];

        $response = static::getAllData('station', $serverNumber, $parameters,
            function ($data) {
                if (empty($data)) {
                    return [];
                }
                $remove_roles = env('ARUBA_ALE_REMOVE_ROLES');
                $remove_roles_arr = !empty($remove_roles) ? explode(',', $remove_roles) : array();
                $stations = array('active' => [], 'other' => []);
                foreach ($data['Station_result'] as $station) {
                    $row = array(
                      'mac_address' => !empty($station['msg']['sta_eth_mac']['addr']) ? format_mac_address($station['msg']['sta_eth_mac']['addr']) : '',
                      'username' => !empty($station['msg']['username']) ? $station['msg']['username'] : '',
                      'role' => !empty($station['msg']['role']) ? $station['msg']['role'] : '',
                    );
                    if (
                      !empty($station['msg']['username'])
                      && !empty($station['msg']['sta_eth_mac']['addr'])
                      && isset($station['msg']['role'])
                      && !in_array($station['msg']['role'], $remove_roles_arr)
                    ) {
                      $stations['active'][] = $row;
                    } else {
                      $stations['other'][] = $row;
                    }
                }

                return $stations;
            }
        );

        if ($other) {
          return $response['other'];
        }

        return $response['active'];
    }

    /**
     * Fetches a list of floors from ALE and maps with Airwave
     * floor IDs based on floor image paths
     *
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getFloors($serverNumber = NULL)
    {

        $parameters = [];

        return static::getAllData('floor', $serverNumber, $parameters,
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
    }

    /**
     * Collect all data from ALE servers
     *
     * @param $method
     * @param $macAddress
     * @param \Closure   $callback
     *
     * @return array
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getAllData($method = null, $serverNumber = NULL, $parameters = [], \Closure $callback = null)
    {
        $ret_val = [];
        if ($method == 'station') {
          $ret_val = array('active' => [], 'other' => []);
        }
        $ale_servers = config('aruba.ale.aleServersCount');

        if (is_null($serverNumber)) {
          $i = 1;
        }
        else {
          $i = $ale_servers = $serverNumber;
        }

        for ($i = 1; $i<=$ale_servers; $i++) {
          $curl = new CurlRequest();
          $curl->setUrl(
              'https://' .
              config('aruba.ale.aleServer'.$i.'.baseUrl') .
              config('aruba.ale.aleServer'.$i.'.apiUrl') . '/' . $method,
              $parameters
          );

          $curl->setAuthentication(config('aruba.ale.aleServer'.$i.'.username'), config('aruba.ale.aleServer'.$i.'.password'));
          $curl->expect(CurlRequest::JSON_RESPONSE, $callback);

          $response = $curl->execute();
          if (!empty($response)) {
            if (is_null($serverNumber)) {
              if ($method == 'station') {
                $ret_val['active'] = array_merge($ret_val['active'], $response['active']);
                $ret_val['other'] = array_merge($ret_val['other'], $response['other']);
              }
              else {
                $ret_val = array_merge($ret_val, $response);
              }
            }
            else {
              $ret_val = $response;
            }
            if (!empty($parameters['sta_eth_mac']) && !empty($ret_val['floor_id'])) {
              break;
            }
          }
        }

        return $ret_val;
    }
}
