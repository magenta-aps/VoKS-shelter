<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Cisco\Cmx\Location;

use BComeSafe\Libraries\CurlRequest;

class CmxLocation
{
    /**
     * Fetches coordinates for a MAC address from Cisco Cmx
     *
     * @param  $macAddress
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getCoordinates($macAddress)
    {
        return static::pullLocations(
            array('macAddress' => strtolower($macAddress)),
            function ($client) {
                if (!empty($client)) {
                  $client = $client[0];
                }
                if (empty($client)
                    || empty($client['macAddress'])
                    || empty($client['username'])
                    ) {
                    return [
                        'x'           => 0,
                        'y'           => 0,
                        'floor_id'    => '',
                        'campus_id'   => '',
                        'mac_address' => '',
                        'username'    => ''
                    ];
                }
                $location = array(
                  'x'           => !empty($client['locationCoordinate']['x']) ? $client['locationCoordinate']['x'] : 0,
                  'y'           => !empty($client['locationCoordinate']['y']) ? $client['locationCoordinate']['y'] : 0,
                  'floor_id'    => !empty($client['floorRefId']) ? $client['floorRefId'] : '',
                  'mac_address' => $client['macAddress'],
                  'username'    => $client['username']
                );

                return $location;
            }
        );
    }


    /**
     * Fetches coordinates for all available devices from Cisco Cmx
     *
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public static function getAllCoordinates()
    {
        return static::pullLocations(
            array('associatedOnly' => 'true'),
            function ($data) {
                $locations = [];
                if (empty($data)) {
                  return $locations;
                }

                foreach ($data as $client) {
                  if (empty($client['macAddress'])) continue;
                  if (empty($client['username'])) continue;
                  if (empty($client['ssid'])) continue;
                  if ($client['ssid'] == 'NOT APPLICABLE') continue;
                  $location = array(
                    'x'           => !empty($client['locationCoordinate']['x']) ? $client['locationCoordinate']['x'] : 0,
                    'y'           => !empty($client['locationCoordinate']['y']) ? $client['locationCoordinate']['y'] : 0,
                    'floor_id'    => !empty($client['floorRefId']) ? $client['floorRefId'] : '',
                    'mac_address' => $client['macAddress'],
                    'username'    => $client['username'],
                    'ssid'        => $client['ssid']
                  );
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
    public static function pullLocations($parameters = array(), \Closure $callback = null)
    {
        if (config('cisco.enabled') === false) {
            return [
                'x'           => 0,
                'y'           => 0,
                'floor_id'    => '',
                'mac_address' => '',
                'username'    => ''
            ];
        }

        $curl = new CurlRequest();
        $curl->setUrl(config('cisco.baseUrl') . config('cisco.api.clients'), $parameters);
        $curl->setAuthentication(config('cisco.username'), config('cisco.password'));
        $curl->expect(CurlRequest::JSON_RESPONSE, $callback);
        $response = $curl->execute();

        return $response;
    }

    /**
      * @param $ipAddress
      *
      * @return macAdress
      * @throws \BComeSafe\Libraries\CurlRequestException
      */
    public static function getMacAddressByIP($ipAddress)
    {
        return static::pullLocations(
            array('ipAddress' => $ipAddress),
            function ($client) {
                if (!empty($client)) {
                  $client = $client[0];
                }
                if (!empty($client['macAddress'])) {
                  return $client['macAddress'];
                }
                return null;
            }
        );
    }
}
