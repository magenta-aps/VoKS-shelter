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
     * Collect data from ALE controllers
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
    public function getData($controller_url, $params) {
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
     * Get Clients from ALE controllers
     *
     * @return array
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    public function getClientsFromControllerUIQuery($controller_url, $params) { 
        $ret_val = array();
        if (!config('aruba.controllers.enabled')) return $ret_val;
        
        if (empty($controller_url)) $ret_val;
        if (empty($params)) $ret_val;
        
        $login_url = $controller_url . ':' . config('aruba.controllers.port') . config('aruba.controllers.login.url');
        $post_request = array();
        $post_request['needxml'] = 0;
        $post_request['opcode'] = 'login';
        $post_request['url'] = config('aruba.controllers.login.url');
        $post_request['uid'] = config('aruba.controllers.login.username');
        $post_request['passwd'] = config('aruba.controllers.login.password');
        $post_request['destination'] = config('aruba.controllers.uiQueryUrl');
        $post_request['next_action'] = config('aruba.controllers.uiQueryUrl');
        
        try {
            $data = (new CurlRequest())
            ->setUrl($login_url)
            ->setCookieJar(config('aruba.cookies.controller'))
            ->setPostRequest($post_request)
            ->expect(
                CurlRequest::CUSTOM_RESPONSE,
                function ($response) {
                    //return Formatter::make($response, Formatter::XML)->toArray();
                    return $response;
                }
            )->execute();
        } catch (\Exception $e) {
            return array(
              'login_url' => $login_url,
              'post_request' => $post_request,
              'error' => $e->getMessage()
            );
        }
        return $data;
    }
    
    /**
     * Collect data from ALE controllers
     * 
     * @param string $device_ip
     * @param int $possible_school_id - will be checked first
     * @return string $ap_name
     */
    public function getAPByIp($device_ip, $possible_school_id = null, $schools = array()) {
      $ret_val = null;
      if (empty($device_ip)) return $ret_val;
      
      // Get all schools list
      if (empty($schools)) {
        $schools = School::whereNotNull('controller_url')->get()->toArray();
        if (empty($schools)) return $ret_val;
        $schools = array_map_by_key($schools, 'id');
      }
      
      // Check possible school first
      if ($possible_school_id && !empty($schools[$possible_school_id])) {
        $device = $this->getData($schools[$possible_school_id]['controller_url'], array('ip' => $device_ip));
        if (!empty($device['location'])) {
          $ret_val = $device['location'];
          return $ret_val;
        }
        else {
          unset($schools[$possible_school_id]);
        }
      }
      
      foreach($schools as $school) {
        $device = $this->getData($school['controller_url'], array('ip' => $device_ip));
        if (!empty($device['location'])) {
          $ret_val = $device['location'];
          break;
        }
      }
      
      return $ret_val;
    }
}
