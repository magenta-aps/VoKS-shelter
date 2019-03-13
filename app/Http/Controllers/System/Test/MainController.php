<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System\Test;

use BComeSafe\Http\Controllers\System\BaseController;
use BComeSafe\Libraries\CurlRequest;
use BComeSafe\Libraries\WakeOnLan;
use BComeSafe\Models\School;
use BComeSafe\Packages\Aruba\Airwave\Base;
use BComeSafe\Packages\Aruba\Airwave\Importer;
use BComeSafe\Packages\Aruba\Airwave\Importer\AirwaveImport;
use BComeSafe\Packages\Aruba\ArubaControllers\ArubaControllers;
use SoapBox\Formatter\Formatter;
use BComeSafe\Packages\Aruba\Airwave\Structure;
use BComeSafe\Packages\Aruba\Ale;
use BComeSafe\Packages\Aruba\Clearpass\Authentication;
use BComeSafe\Packages\Aruba\Clearpass\User;
use BComeSafe\Packages\Notifications;
use BComeSafe\Packages\Coordinates\Coordinates;
use BComeSafe\Models\SchoolDefault;
use BComeSafe\Models\Aps;
use Devristo\Phpws\Client;
use React\EventLoop;
use Zend\Log;
use BComeSafe\Models\Device;
use Mail;


/**
 * Class MainController
 *
 * @package  BComeSafe\Http\Controllers\System\Test
 */
class MainController extends BaseController
{

    public function getSettings() {
      phpinfo();
      return [];
    }

    public function getCoords() {
      if (!empty($_GET['macs'])) {
        $macs = is_array($_GET['macs']) ? $_GET['macs'] : array($_GET['macs']);
        \Artisan::call('sync:macs', $macs);
      }
    }

    public function getUser() {
        $user = new User();
        return $user->getByIp('192.168.21.54');
    }

    public function getRegister() { 
      try {
        $device = Device::where('device_id','=',@$_GET['device_id'])->get()->first();

        echo '<pre>';
        print_r($device->id);
        echo '<br/>';
        print_r($device->school_id);
        echo '<br/>';
        print_r($device->device_type);
        echo '<br/>';
        print_r($device->device_id);
        echo '<br/>';
        print_r($device->fullname);
        echo '<br/>';
        print_r($device->mac_address);
        echo '<br/>';
        print_r($device->floor_id);
        echo '<br/>';
        print_r($device->x);
        echo '<br/>';
        print_r($device->y);
        echo '<br/>';
        print_r($device->ap_name);
        echo '<br/>';
        echo '</pre>';

        $device->updateDeviceProfile();

      } catch (\Exception $e) {
        echo 'ERROR';
        echo '<pre>';
        print_r($e->getCode());
        echo '</pre>';
        echo '<pre>';
        print_r($e->getMessage());
        echo '</pre>';
        die(__FILE__);


      }
      echo '<pre>';
      print_r($device);
      echo '</pre>';
      die(__FILE__);
    }

    public function getPush() {

        if (empty($_GET['gcm_id'])) {
          echo 'Erro: Missing "gcm_id" parameter.';
          die();
        }
        $gcm_id = $_GET['gcm_id'];

        if (empty($_GET['type'])) {
          echo 'Erro: Missing "type" parameter.';
          die();
        }
        $type = $_GET['type']; //ios OR android OR desktop

        $devices = array(
          ['id' => $gcm_id, 'type' => $type]
        );
        $message = 'Hi!. This is a test push message sent from BCS. Did you received?';

        echo 'Android config:';
        echo '<pre>';
        print_r(config('push.android'));
        echo '</pre>';
        echo '<br />';

        echo 'Ios config:';
        echo '<pre>';
        print_r(config('push.ios'));
        echo '</pre>';
        echo '<br />';
        echo '<br />';
        echo 'GCM_ID: ', $gcm_id, '<br />';
        echo 'TYPE: ', $type, '<br />';
        echo 'MESSAGE: ', $message, '<br />';
        echo '<br />';
        echo 'Sending Push Notification. <br /><br />';

        $item = new Notifications\Item($message, isset($_GET['notification_id']) ? $_GET['notification_id'] : 1);
        $server = new Notifications\Server\Server($item);

        if ($type == 'android') {
          $server->registerDevicePusher(new Notifications\Pusher\Android(config('push.android')));
        }
        else if ($type == 'ios') {
          $server->registerDevicePusher(new Notifications\Pusher\iOS(config('push.ios')));
        }
        else {
          $server->registerDevicePusher(new Notifications\Pusher\Desktop());
        }
        $result = $server->send($devices);
        echo 'Result:';
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        return;
    }

    public function getCoordinateStats($full = 0) {
        $school = \DB::select(
            "
            SELECT count(d.id) AS coordinates, s.name as school_name
            FROM devices AS d
            INNER JOIN schools AS s ON s.id = d.school_id
            WHERE d.active = 1
            GROUP BY s.id
            "
        );

        echo '<pre>';
        print_r($school);
        echo '</pre>';

        if ($full) {
            $devices = \DB::select(
                "
            SELECT d.mac_address, s.id as school_id, d.x, d.y, s.name as school_name
            FROM devices AS d
            INNER JOIN schools AS s ON s.id = d.school_id
            WHERE d.active = 1 AND LENGTH(d.x) > 0
            GROUP BY d.id
            "
            );
        }
    }
    
    public function getEmail() { 
        if (!config('mail.enabled')) echo 'Mail is disabled.<br />';
        echo 'From email address is: '.(!empty(config('mail.from.address')) ? config('mail.from.address') : 'Empty').'<br />';
        echo 'From name is: '.(!empty(config('mail.from.name')) ? config('mail.from.name') : 'Empty').'<br />';
        echo 'Subject is: '.(!empty(trans('mail.alarm.test.subject')) ? trans('mail.alarm.test.subject') : 'Empty').'<br />';
        $test_send = config('mail.test_send');
        echo 'Test send is: '.(!empty($test_send) ? 'Enabled' : 'Disabled').'<br />';
        $test_email = config('mail.test_email');
        echo 'Test email is: '.(!empty($test_email) ? $test_email : 'Empty').'<br />';
        echo 'Mail driver:'.(config('mail.driver')?  config('mail.driver'):'Empty').'<br />';
        echo 'Mail host: '.(config('mail.host')?  config('mail.host'):'Empty').'<br />';
        echo 'Mail port: '.(config('mail.port')?  config('mail.port'):'Empty').'<br />';
        echo 'Mail username: '.(config('mail.username')?  config('mail.username'):'Empty').'<br />';
        echo 'Mail password: '.(config('mail.password')?  config('mail.password'):'Empty').'<br />';
        
        //
        $emails = array();
        if (!empty($test_email) && !empty($test_send)) $emails[] = $test_email;
        
        $email_to = !empty($_GET['email']) ? $_GET['email'] : '';
        if (empty($email_to)) {
          echo 'Info: Missing GET <i>email</i> parameter.<br />';
        } 
        else {
          $emails[] = $email_to;
        }
        
        if (empty($emails)) {
          echo 'Error: Missing emails to send to.<br />';
          die('Stopped.');
        }

        if (empty($_GET['send_email'])) {
          echo 'To send Email - add GET parameter <i>send_email=1</i>. <br />';
        }
        else {
          echo 'Sending Email. <br /><br />';

          $message = 'Hi!. This is a TEST email sent from BCS. Did you received?';

          foreach($emails as $email) {
            $result = Mail::raw($message, function($message) use ($email) {
              $message
                ->from(config('mail.from.address'), config('mail.from.name'))
                ->to($email)
                ->subject(trans('mail.alarm.test.subject'));
            });
          }
          echo 'Result:' . $result . '<br /><br />';
        }
        
        echo 'Finished.';
        return;
    }
    
    public function getSms() { 
      if (!config('sms.enabled')) echo 'SMS is disabled.<br />';
        $sms_providers = \Component::get('Sms')->getIntegrations();
        echo 'SMS Providers: <pre>';
        print_r($sms_providers);
        echo '</pre>';
        echo '<br />';

        //
        $defaults = SchoolDefault::getDefaults();
        echo 'Default provider: ' . $defaults->sms_provider;
        echo '<br />';

        //
        $provider = $_GET['provider'];
        if (empty($provider)) {
          echo 'Erro: Missing provider parameter.';
          die();
        }

        //
        $phone_number = $_GET['phone_number'];
        if (empty($phone_number)) {
          echo 'Erro: Missing phone_number parameter.';
          die();
        }
        
        if (empty($_GET['send_sms'])) {
          echo 'To send Email - add GET parameter <i>send_sms=1</i>. <br />';
        }
        else {
          echo 'Sending SMS. <br /><br />';
          $integration = \Component::get('Sms')->getIntegration($provider);
          $message = 'Hi!. This is a test message sent from BCS. Did you received?';
          $result = $integration->sendMessage($phone_number, $message, TRUE);
          echo 'Result:' . $result;
        }
        
        echo 'Finished.';
        return;
    }

    public function getAle() {
        return Ale\AleLocation::getCoordinates(@$_GET['mac']);
    }

    public function getWol() {
        try {
            $ip = isset($_GET['ip']) ? $_GET['ip'] : '';
            $mac = isset($_GET['mac']) ? $_GET['mac'] : '';
            $port = isset($_GET['port']) ? $_GET['port'] : 7;

            $response = ['success' => WakeOnLan::sendMagicPacket($ip, $mac, $port), 'messages' => WakeOnLan::getMessages()];
            d($response);

        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * Test UCP methods
     *
     * URL: /system/test/ucp-test-methods
     */
    public function getUcpTestMethods() {
        $options = config('ucp');
        $ucp = new \UCP\Client($options);

        // Authenticate
        $username = $options['username'];
        $password = $options['password'];
        $ucp->auth()->login($username, $password);

        // SMS text message
        //            $success = $ucp->message()->send('37061490463', 'UCP Test message');
        //            dd($success);

        // Play audio message
        // - nodeId
        // - voiceId
        // - groupId
        // - interruptId
        // - repeat
        $response = $ucp->broadcast()->play(1, 4, 21, 0, 0);
        dd($response);

        echo 'If you see this, UCP authentication has worked out perfectly.';
    }
    
    /**
     * Test Airwave structure
     *
     * URL: /system/test/airwave-structure
     */
    public function getAirwaveStructure() {
      
      $AirwaveImport = new AirwaveImport();
      $options = $AirwaveImport->getOptions();
      
      echo 'Welcome to Airwav sync test. <br />';
      echo 'For Sync campuses - no need any GET parameter. <br />';      
      echo 'For Sync aps - use GET parameter: <i>sync_aps=1</i> and <i>site_id=<floor_ale_id></i>. <br />';
      echo 'For Sync floors - use GET parameter: <i>sync_floors=1</i>. <br />';
      
      echo "Options:<br /><pre>";
      print_r($options);
      echo "</pre>";
      echo '<br /><br />';
      
      if (empty($_GET['start_sync'])) {
        echo 'To start Sync - add GET parameter <i>start_sync=1</i>. <br />';
      }
      else {
        
        //Sync Aps
        if (!empty($_GET['sync_aps'])) {
          $options['loginData']['destination'] = $options['api_url']['aps'];
          $options['loginData']['next_action'] = $options['api_url']['aps'];
          if (!empty($_GET['site_id'])) {
            $options['loginData']['destination'] = $options['api_url']['aps'] . '?site_id=' . $_GET['site_id'];
            $options['loginData']['next_action'] = $options['api_url']['aps'] . '?site_id=' . $_GET['site_id'];
          }
        }
        
        //Sync Floors
        if (!empty($_GET['sync_floors'])) {
          $options['loginData']['destination'] = $options['api_url']['sites'];
          $options['loginData']['next_action'] = $options['api_url']['sites'];
        }
        
        //Sync
        $data = (new CurlRequest())
          ->setUrl($options['loginUrl'])
          ->setCookieJar($options['cookiePath'])
          ->setPostRequest($options['loginData'])
          ->expect(
              CurlRequest::CUSTOM_RESPONSE,
              function ($response) {
                echo "<pre>";
                print_r($response);
                echo "</pre>";
                return Formatter::make($response, Formatter::XML)->toArray();
              }
          )->execute();
        //
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        
        //Test AP structure
        if (!empty($_GET['sync_aps']) && !empty($data['ap'])) {
          foreach ($data['ap'] as $ap) {
            $structure = isset($ap['@attributes']) ? $ap['@attributes'] : $ap;
            if (!isset($structure['id'])) {
              continue;
            }
            echo "<pre>";
            print_r($structure);
            echo "</pre>";
          }
        }
      }
      //
      echo '<br />';
      echo 'Finished.';
      return;
    }
    
    /**
     * Test Aruba Controller
     *
     * URL: /system/test/aruba-controller
     */
    public function getArubaController() {
      
      $AurbaControllers = new ArubaControllers();
      
      echo 'Welcome to Aruba Controller test. <br />';
      echo 'use GET parameter: <br />'
      . '<i>school_id=<school_id></i> <br /><br />'
      . 'AND <i>device_ip=<ip_address></i> <br />'
      . 'OR <i>device_mac=<mac_address></i> <br />'
      . 'OR <i>device_username=<username></i>';
      echo '<br /><br />';
      
      $schools = School::whereNotNull('controller_url')->get()->toArray();
      if (!empty($schools)) {
        $schools = array_map_by_key($schools, 'id');
      }
      
      //Aps
      $aps = Aps::get()->toArray();
      if (!empty($aps)) {
        $aps = array_map_by_key($aps, 'ap_name');
      }
              
      //By IP
      if (!empty($_GET['device_ip'])) {
        echo 'Search AP name by IP only <br />';
        $ap_name = $AurbaControllers->getAPByIp($_GET['device_ip'], null, $schools);
        echo 'App name: ' . $ap_name;
        if (!empty($aps[$ap_name])) {
          if (!empty($schools[$aps[$ap_name]['school_id']])) {
            echo 'School by Ap name:';
            echo "<pre>";
            print_r($schools[$aps[$ap_name]['school_id']]);
            echo "</pre>";
          }
          else {
            echo 'School not found.';
          }
        }
      }
      //
      if (!empty($_GET['school_id'])) {
        $school = School::where('id', '=', $_GET['school_id'])->first()->toArray();
        echo "School data: <pre>";
        print_r($school);
        echo "</pre>";
        echo '<br />';
        
        if (empty($school['controller_url'])) {
          echo 'Missing Controller URL';
          echo '<br />';
          echo 'Finished.';
          return;
        }
        else {
          echo 'Controller Ready. <br />';
        }
        
        $params = array();
        if (!empty($_GET['device_ip'])) {
          $params['ip'] = $_GET['device_ip'];
        }
        
        if (!empty($_GET['device_mac'])) {
          $params['mac_address'] = $_GET['device_mac'];
        }
        
        if (!empty($_GET['device_username'])) {
          $params['username'] = $_GET['device_username'];
        }
        
        if (empty($params)) {
          echo 'Missing device parameters.';
        }
        else {
          //DB
          if (!empty($_GET['device_ip'])) {
            echo 'Device data in DB by IP: <br />';
            $device = Device::where('ip_address','=',$_GET['device_ip'])->get()->first();
            if (!empty($device)) {
              echo "<pre>";
              print_r($device->toArray());
              echo "</pre>";
            }
            else {
              echo 'Didn\'t found in database';
            }
          }
          foreach($params as $k => $p) {
            echo "Searching in Controller by parameter: " . $k;
            $device_controller = $AurbaControllers->getData($school['controller_url'], array($k => $p));
            echo "<pre>";
            print_r($device_controller);
            echo "</pre>";
          }
          //DB
          if (!empty($device_controller['macaddr'])) {
            echo 'Device data in DB by mac_address from Controller: <br />';
            $device = Device::where('mac_address','=',$device_controller['macaddr'])->get()->first()->toArray();
            echo "<pre>";
            print_r($device);
            echo "</pre>";
          }
        }
      } 
      
      //
      echo '<hr>';
      echo "Available schools: <pre>";
      print_r($schools);
      echo "</pre>";
      
      echo '<br />';
      echo 'Finished.';
      return;
    }
}
