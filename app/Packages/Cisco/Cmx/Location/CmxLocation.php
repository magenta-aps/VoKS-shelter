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
            $macAddress,
            null,
            function ($client) {
                if (!empty($client)) {
                  $client = $client[0];
                }
                if (empty($client)
                    || empty($client['macAddress'])
                    || empty($client['ssId'])
                    || empty($client['userName'])
                    || $client['networkStatus'] != 'ACTIVE'
                    ) {
                    return [
                        'x'           => 0,
                        'y'           => 0,
                        'floor_id'    => '',
                        'campus_id'   => '',
                        'mac_address' => '',
                        'username'    => '',
                        'ss_id'       => '',
                        'active'      => 0
                    ];
                }
                $location = array(
                  'x'           => !empty($client['mapCoordinate']['x']) ? $client['mapCoordinate']['x'] : 0,
                  'y'           => !empty($client['mapCoordinate']['y']) ? $client['mapCoordinate']['y'] : 0,
                  'floor_id'    => !empty($client['mapInfo']['floorRefId']) ? $client['mapInfo']['floorRefId'] : '',
                  'mac_address' => $client['macAddress'],
                  'username'    => $client['userName'],
                  'ss_id'       => $client['ssId'],
                  'active'      => $client['networkStatus'] == 'ACTIVE' ? 1 : 0
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
    public static function getAllCoordinates($page)
    {
        return static::pullLocations(
            null,
            $page,
            function ($data) {
                $locations = [];
                if (empty($data)) {
                  return $locations;
                }

                foreach ($data as $client) {
                  if (empty($client['ssId'])) continue;
                  if (empty($client['userName'])) continue;
                  if ($client['networkStatus'] != 'ACTIVE') continue;
                  $location = array(
                    'x'           => !empty($client['mapCoordinate']['x']) ? $client['mapCoordinate']['x'] : 0,
                    'y'           => !empty($client['mapCoordinate']['y']) ? $client['mapCoordinate']['y'] : 0,
                    'floor_id'    => !empty($client['mapInfo']['floorRefId']) ? $client['mapInfo']['floorRefId'] : '',
                    'mac_address' => $client['macAddress'],
                    'username'    => $client['userName'],
                    'ss_id'       => $client['ssId'],
                    'active'      => $client['networkStatus'] == 'ACTIVE' ? 1 : 0
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
    public static function pullLocations($macAddress = null, $page = null, \Closure $callback = null)
    {
        if (config('cisco.enabled') === false) {
            return [
                'x'           => 0,
                'y'           => 0,
                'floor_id'    => '',
                'mac_address' => '',
                'username'    => '',
                'ss_id'       => '',
                'active'      => 0
            ];
        }

        $parameters = [];
        $parameters['association'] = true;
        if (!empty($macAddress)) {
          $parameters['macAddress'] = $macAddress;
        }
        if (!empty($page)) {
          $parameters['include'] = 'metadata';
          $parameters['page'] = $page;
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
      * @param \Closure $callback
      *
      * @return macAdress
      * @throws \BComeSafe\Libraries\CurlRequestException
      */
    public static function getMacAddressByIP($ipAddress, \Closure $callback = null)
    {
        $parameters = [];
        if (!empty($ipAddress)) {
          $parameters['ipAddress'] = $ipAddress;
        }

        $curl = new CurlRequest();
        $curl->setUrl(config('cisco.baseUrl') . config('cisco.api.clients'), $parameters);
        $curl->setAuthentication(config('cisco.username'), config('cisco.password'));
        $curl->expect(CurlRequest::JSON_RESPONSE, $callback);
        $response = $curl->execute();

        if (isset($response[0]['macAddress'])) {
            return $response[0]['macAddress'];
        }

        return null;
    }
}
