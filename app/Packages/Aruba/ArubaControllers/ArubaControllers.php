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
    public static function getData($school_id, $params) {
        $ret_val = array();
        if (!config('aruba.controllers.enabled')) return $ret_val;
        
        if (empty($params)) $ret_val;
        
        if (!empty($params['ip'])) {
          $post_param = '<ipaddr>'. $params['ip'] .'</ipaddr>';
        }
        elseif (!empty($params['mac_address'])) {
          $post_param = '<macaddr>'. $params['mac_address'] .'</macaddr>';
        }
        elseif (!empty($params['username'])) {
          $post_param = '<name>'. $params['mac_address'] .'</name>';
        }
        
        //Schools
        $school = School::where('id', '=', $school_id)->first();
        
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
            ->setUrl($school->controller_url . '/auth/command.xml')
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
}
