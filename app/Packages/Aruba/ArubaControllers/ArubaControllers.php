<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Aruba\ArubaControllers;

use BComeSafe\Libraries\CurlRequest;
use BComeSafe\Models\School;
use SoapBox\Formatter\Formatter;

/**
 * Class ArubaControllers
 *
 * @package BComeSafe\Packages\Aruba\ArubaControllers
 */
class ArubaControllers {

    /**
     * Aruba Controllers ArubaOS 6.x: Get single client data
     *
     * @return array
     * Response exmaple:
          <aruba>
            <status>Ok</status>
            <macaddr>[Mac address]</macaddr>
            <ipaddr>[IP address]</ipaddr>
            <name>[username]</name>
            <role>[Role]</role>
            <type>Wireless</type>
            <vlan>200</vlan>
            <location>[Ap name]</location>
            <age>00:00:26</age>
            <auth_status>Authenticated</auth_status>
            <auth_server>[Auth server]</auth_server>
            <auth_method>802.1x</auth_method>
            <essid>[SSID]</essid>
            <bssid>[BSSID]</bssid>
            <phy_type>a-VHT-80</phy_type>
            <mobility_state>Wireless</mobility_state>
            <in_packets>264</in_packets>
            <in_octets>45116</in_octets>
            <out_packets>230</out_packets>
            <out_octets>74700</out_octets>
          </aruba>
     */
    public function getClientFromControllerOS6x($controller_url, $params) {
        $ret_val = array();
        if (!config('aruba.controllers.enabled')) return $ret_val;
        
        if (empty($controller_url)) $ret_val;
        if (empty($params)) $ret_val;
        $post_param = '';
        if (!empty($params['ip'])) {
          $post_param = '<ipaddr>'. $params['ip'] .'</ipaddr>';
        }
        elseif (!empty($params['mac_address'])) {
          $post_param = '<macaddr>'. $params['mac_address'] .'</macaddr>';
        }
        elseif (!empty($params['username'])) {
          $post_param = '<name>'. $params['username'] .'</name>';
        }
        
        $headers = array('Content-type: application/xml');
        $post = array(
          'xml' => '<aruba command="user_query">
            '.$post_param.'
            <authentication>cleartext</authentication>
            <key>'.config('aruba.controllers.key').'</key>
            <version>1.0</version>
          </aruba>'
        );
        
        $ret_val = (new CurlRequest())
            ->setUrl($controller_url . '/auth/command.xml')
            ->setHeaders($headers)
            ->setPostRequest($post)
            ->expect(
                CurlRequest::CUSTOM_RESPONSE,
                function ($response) {
                    return Formatter::make($response, Formatter::XML)->toArray();
                }
            )->execute();

        return $ret_val;
    }
    
    /**
     * Aruba Controllers ArubaOS 8.x: Login
     *
     * @return array
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public function loginToArubaControllerOS8x($controller_url) {
      $ret_val = array();
      if (!config('aruba.controllers.enabled')) return $ret_val;
      
      if (empty($controller_url)) $ret_val;
      
      $url = $controller_url . ':' . config('aruba.controllers.port') . config('aruba.controllers.login.url');
      $params = [
        'username' => config('aruba.controllers.login.username'),
        'password' => config('aruba.controllers.login.password')
      ];
      try {
          $data = (new CurlRequest())
          ->setUrl($url)
          ->setCookieJar(config('aruba.cookies.controller'))
          ->setPostRequest($params)
          ->expect(
              CurlRequest::JSON_RESPONSE,
              function ($response) {
                return $response;
              }
          )->execute();
      } catch (\Exception $e) {
          return array(
            'url' => $url,
            'post_request' => $params,
            'error' => $e->getMessage()
          );
      }
      return $data;
      
    }
    
    /**
     * Aruba Controllers ArubaOS 8.x: Logout
     *
     * @return array
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public function logoutFromArubaControllerOS8x($controller_url) {
      $ret_val = array();
      if (!config('aruba.controllers.enabled')) return $ret_val;
      
      if (empty($controller_url)) $ret_val;
      
      $url = $controller_url . ':' . config('aruba.controllers.port') . config('aruba.controllers.logout.url');
      $params = [];
      try {
          $data = (new CurlRequest())
          ->setUrl($url)
          ->setCookieJar(config('aruba.cookies.controller'))
          ->expect(
              CurlRequest::JSON_RESPONSE,
              function ($response) {
                return $response;
              }
          )->execute();
      } catch (\Exception $e) {
          return array(
            'url' => $url,
            'params' => $params,
            'error' => $e->getMessage()
          );
      }
      return $data;
      
    }
    
    /**
     * Aruba Controllers ArubaOS 8.x: Get all clients
     *
     * @return array
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public function getClientsFromControllerOS8x($controller_url, $params) { 
      $ret_val = array();
      if (!config('aruba.controllers.enabled')) return $ret_val;

      if (empty($controller_url)) $ret_val;
      if (empty($params)) $ret_val;
      
      $params['command'] = 'show+user-table';
      $url = $controller_url . ':' . config('aruba.controllers.port') . config('aruba.controllers.devices.url');
      $url .= '?command=' . $params['command'] . '&UIDARUBA=' . $params['UIDARUBA'];
      
      try {
          $data = (new CurlRequest())
          ->setUrl($url)
          ->setCookieFile(config('aruba.cookies.controller'))
          ->expect(
              CurlRequest::JSON_RESPONSE,
              function ($response) {
                if (!empty($response['Users'])) {
                  return $response['Users'];
                }
                return [];
              }
          )->execute();
      } catch (\Exception $e) {
          return array(
            'url' => $url,
            'params' => $params,
            'error' => $e->getMessage()
          );
      }
      return $data;
    }
    
    /**
     * Aruba Controllers: Get all clients
     *
     * @return array
     */
    public function getClientsFromController($controller_url, $aruba_os = '8.x') {
      $ret_val = array();
      if (!config('aruba.controllers.enabled')) return $ret_val;
      
      if (empty($controller_url)) $ret_val;
      $clients = [];
      
      switch($aruba_os) {
        case '8.x':
          $data = $this->loginToArubaControllerOS8x($controller_url);
          if (empty($data)) {
            return $clients;
          }
          $params = array();
          $params['UIDARUBA'] = $data['_global_result']['UIDARUBA'];
          $clients = $this->getClientsFromControllerOS8x($controller_url, $params);
          
          $this->logoutFromArubaControllerOS8x($controller_url);
        default;
      }
      
      return $clients;
      
    }
    
    /**
     * Aruba Controllers: Get AP name by IP
     * 
     * @param string $device_ip
     * @param int $possible_school_id - will be checked first
     * @param array $schools - all schools
     * @return string $ap_name
     */
    public function getAPByIp($device_ip, $possible_school_id = null, $schools = array()) {
      $ret_val = null;
      if (empty($device_ip)) return $ret_val;
      
      //@Todo - get single possible school first from DB and check for device data.
      
      // Get all schools list
      if (empty($schools)) {
        $schools = School::whereNotNull('controller_url')->get()->toArray();
        if (empty($schools)) return $ret_val;
        $schools = array_map_by_key($schools, 'id');
      }
      
      // Check possible school first
      if ($possible_school_id && !empty($schools[$possible_school_id])) {
        $device = $this->getClientFromControllerOS6x($schools[$possible_school_id]['controller_url'], array('ip' => $device_ip));
        if (!empty($device['location'])) {
          $ret_val = $device['location'];
          return $ret_val;
        }
        else {
          unset($schools[$possible_school_id]);
        }
      }
      
      foreach($schools as $school) {
        $device = $this->getClientFromControllerOS6x($school['controller_url'], array('ip' => $device_ip));
        if (!empty($device['location'])) {
          $ret_val = $device['location'];
          break;
        }
      }
      
      return $ret_val;
    }
    
    /**
     * Aruba Controllers: check Client Role
     * 
     * @param string $role
     * @return boolean
     */
    public function checkClientRole($role) {
      
      $roles_remove = config('aruba.roles.remove');
      
      if (empty($roles_remove)) {
        return TRUE;
      }
            
      $roles_remove_arr = explode(',', $roles_remove);
      if (in_array($role, $roles_remove_arr)) {
        return FALSE;
      }
      return TRUE;
    }
}
