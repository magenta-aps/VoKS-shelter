<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Aruba\ArubaControllers;

class ArubaControllers
{

    /**
     * Collect data from ALE controllers
     *
     * @return array
     */
    public static function getData()
    {
        $ret_val = [];
        $controllers_servers = config('aruba.controllers.urls');
        $servers = explode(',', $controllers_servers);
        $username = config('aruba.controllers.username');
        $password = config('aruba.controllers.password');

        if (empty($servers)) {
          return $ret_val;
        }
        
        foreach($servers as $url) {
        
          /*$curl = new CurlRequest();
          $curl->setUrl(
              'ssh ' . config('aruba.controllers.username') . ':' . config('aruba.controllers.password') . '@' . $url
          );

          $curl->setAuthentication(config('aruba.ale.controllers.username'), config('aruba.ale.controllers.password'));
          //$curl->expect(CurlRequest::PLAIN_RESPONSE, $callback);

          $response = $curl->execute();
          echo '<pre>';
            print_r($response);
            echo '</pre>';
            die(__FILE__);
          if (!empty($response)) {

          }*/
          //echo `sshpass -p "{$password}" ssh -o StrictHostKeyChecking=no -X {$username}@{$url} ('show user')`;
          //die();
        }

        return $ret_val;
    }
}
