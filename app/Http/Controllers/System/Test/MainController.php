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

    /**
     * Aruba Controller: Sync macs
     *
     * URL: /system/test/coords
     */
    public function getCoords() {
      if (!empty($_GET['macs'])) {
        $macs = is_array($_GET['macs']) ? $_GET['macs'] : array($_GET['macs']);
        //['mac:1' => '<add mac address here>', 'mac:2' => '<add mac address here>'];
        \Artisan::call('sync:macs', $macs);
      }
    }
    
    public function getUser() {
        $user = new User();
        return $user->getByIp($_GET['id_address']);
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
     * URL: /system/test/aruba-controller-clients-old
     */
    public function getArubaControllerClientsOld() {
      
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
        $ap_name = $AurbaControllers->getAPByParams(['ip' => $_GET['device_ip']], null, $schools);
        if (!empty($ap_name)) {
          echo 'App name: ' . $ap_name . '<br />';
          if (!empty($aps[$ap_name])) {
            echo 'Ap by Ap name: <br />';
            echo "<pre>";
            print_r($aps[$ap_name]);
            echo "</pre>";
            //
            if (!empty($schools[$aps[$ap_name]['school_id']])) {
              echo 'School by Ap name: <br />';
              echo "<pre>";
              print_r($schools[$aps[$ap_name]['school_id']]);
              echo "</pre>";
            }
            else {
              echo 'School not found. <br />';
            }
          }
          else {
            echo 'Ap not found. <br />';
          }
        }
        else {
          echo 'Ap name not found in Controllers. <br />';
        }
      }
      //
      if (!empty($_GET['school_id'])) {
        echo '<hr />';
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
          echo 'Controller Ready.  <br />';
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
          echo 'Missing device parameters. <br />';
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
              echo 'Didn\'t found in database. <br />';
            }
          }
          foreach($params as $k => $p) {
            echo "Searching in Controller by parameter: " . $k;
            $device_controller = $AurbaControllers->getClientFromControllerOS6x($school['controller_url'], array($k => $p));
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
    
    /**
     * Get all translations
     *
     * URL: /system/test/translations
     */
    public function getTranslations() {
      
      $directories = \File::directories(base_path('resources/lang'));
      $skip_files = array('languages');
      $languages = [];
      for ($i = 0; $i < count($directories); $i++) {
          $code = basename($directories[$i]);
          $languages[$code] = array(
            'title' => \Lang::get('languages.' .$code),
            'files' => array(),
          );
          
          $files = \File::files(base_path('resources/lang/' . $code));
          if (!empty($files)) {
            foreach($files as $f) {
              $final_name = substr(basename($f), 0, -4);
              if (in_array($final_name, $skip_files)) continue;
              $languages[$code]['translations'][$final_name] = self::getTranslationValues($final_name, $code);
            }
          }
          
          //
          $dirs = \File::directories(base_path('resources/lang/' . $code));
          foreach($dirs as $d) {
            $dd = basename($d);
            $files = \File::files(base_path('resources/lang/' . $code . '/' . $dd));
            if (!empty($files)) {
              foreach($files as $f) {
                $final_name = $dd . '/' . substr(basename($f), 0, -4);
                if (in_array($final_name, $skip_files)) continue;
                $languages[$code]['translations'][$final_name] = self::getTranslationValues($final_name, $code);
              }
            }
          }
      }
      
      if (empty($languages)) {
        echo 'No Languages found.';
        echo 'Finished.';
        return;
      }
      $output = '';
      $output.= '<table border=1>';
      $output.= '<tr>';
      foreach($languages as $code => $lang) {
          $output.= '<td style="vertical-align:top;">';
          $counter[$code]=0;
            $output.= '<table>';
            $output.= '<tr>';
              $output.= '<th style="border-bottom:1px solid #000;">'. $lang['title'] . '</th>';
              $output.= '<th style="border-bottom:1px solid #000;"></th>';
            $output.= '</tr>';
            foreach($lang['translations'] as $k => $t) {
              if (empty($t)) {
                $output.= '<tr>';
                  $output.= '<td style="border-bottom:1px solid #000;">';
                    $output.= $k;
                  $output.= '</td>';
                  $output.= '<td>';
                  $output.= '</td>';
                $output.= '</tr>';
              }
              foreach($t as $m => $l) {
                $output.= '<tr>';
                  $output.= '<td style="border-bottom:1px solid #000;">';
                    $output.= $k . '.' . $m;
                  $output.= '</td>';
                  $output.= '<td style="border-bottom:1px solid #000;">';
                    if (!is_array($l)) {
                      if (!empty($l)) {
                        $output.= htmlspecialchars($l);
                        $counter[$code]++;
                      }
                    }
                    else {
                      if (!empty($l)) {
                        $output .='Array()';
                      }
                    }
                  $output.= '</td>';
                $output.= '</tr>';
              }
            }
            $output.= '</table>';
          $output.= '</td>';
      }
      $output.= '</tr>';
      $output.= '</table>';
      foreach($counter as $cc => $c) {
        echo 'Language ' . $cc . ' has ' . $c . ' lines <br />';
      }
      echo $output;
      return;
    }
    
    private function getTranslationValues($name, $lang) {
      $trans = \Lang::get($name, array(), $lang);
      $ret_val = array();
      if (!empty($trans)) {
        if (is_array($trans)) {
          foreach($trans as $k1 => $t1) {
            if (is_array($t1) && !empty($t1)) {
              //
              foreach($t1 as $k2 => $t2) {
                if (is_array($t2) && !empty($t2)) {
                  //
                  foreach($t2 as $k3 => $t3) {
                    if (is_array($t3) && !empty($t3)) {
                      //
                      foreach($t3 as $k4 => $t4) {
                        if (is_array($t4) && !empty($t4)) {
                          //
                          foreach($t4 as $k5 => $t5) {
                            if (is_array($t5) && !empty($t5)) {
                              echo "<pre>";
                              print_r($k5);
                              echo "</pre>";
                              echo "<pre>";
                              print_r($t5);
                              echo "</pre>";
                              die(__FILE__);
                            }
                            else {
                              $ret_val[$k1 . '.' . $k2 . '.' . $k3 . '.' . $k4 . '.' . $k5] = $t5;
                            }
                          }
                        }
                        else{
                          $ret_val[$k1 . '.' . $k2 . '.' . $k3 . '.' . $k4] = $t4;
                        }
                      }
                    } else {
                      $ret_val[$k1 . '.' . $k2 . '.' . $k3] = $t3;
                    }
                  }
                }
                else {
                  $ret_val[$k1 . '.' . $k2] = $t2;
                }
              }
            }
            else {
              $ret_val[$k1] = $t1;
            }
          } 
        }
      }
      return $ret_val;
    }
    
    /**
     * Test Aruba Controller Clients
     *
     * URL: /system/test/aruba-controller-clients
     */
    public function getArubaControllerClients() {
      
      $AurbaControllers = new ArubaControllers();
      
      echo 'Welcome to Aruba Controller ArubaOS 6.x / 8.x API Clients test. <br />';
      echo 'use GET parameter: <br />'
      . '<i>school_id=<school_id></i> <br /><br />'
      . 'AND <i>device_ip=<ip_address></i> <br />'
      . 'OR <i>device_mac=<mac_address></i> <br />'
      . 'OR <i>device_username=<username></i>';
      echo '<br /><br />';
      echo '<br />';
      
      $schools = School::whereNotNull('controller_url')->get()->toArray();
      if (!empty($schools)) {
        $schools = array_map_by_key($schools, 'id');
      }
      
      //
      if (!empty($_GET['school_id'])) {
        echo '<hr />';
        $school = School::where('id', '=', $_GET['school_id'])->first()->toArray();
        echo "School data: <pre>";
        print_r($school);
        echo "</pre>";
        echo '<br />';
        $controller_url = $school['controller_url'];
      }
      
      if (empty($controller_url)) {
        //
        echo 'Missing Controller URL';
        echo '<br />';
        echo '<br />';
        echo "Available schools: <pre>";
        print_r($schools);
        echo "</pre>";
        echo 'Finished.';
        return;
      }
      
      echo 'Controller Ready: <br />';
      echo 'Controller URL: ' . $controller_url . '<br />';
      echo 'Controller Version: ' . $school['controller_version'] . '<br />';
      echo '<br />';
      
      //Get Clients / Client
      $params = array();
      if (!empty($_GET['device_ip'])) {
        $params['ip'] = $_GET['device_ip'];
        echo 'Searching device by IP: ', $params['ip'] . '<br />';
      }
      if (!empty($_GET['device_mac'])) {
        $params['mac_address'] = $_GET['device_mac'];
        echo 'Searching device by Mac: ', $params['mac_address'] . '<br />';
      }
      if (!empty($_GET['device_username'])) {
        $params['username'] = $_GET['device_username'];
        echo 'Searching device by Username: ', $params['username'] . '<br />';
      }
      if (!empty($params)) {
        $data = $AurbaControllers->getClientFromController($controller_url, $params, $school['controller_version']);
        
        //Aps
        $aps = Aps::get()->toArray();
        if (!empty($aps)) {
          $aps = array_map_by_key($aps, 'ap_name');
        }
        $ap_name = $AurbaControllers->getAPByParams($params, null, $schools);
        if (!empty($ap_name)) {
          echo 'Found AP name: ' . $ap_name . '<br />';
          if (!empty($aps[$ap_name])) {
            echo 'AP by AP name: <br />';
            echo "<pre>";
            print_r($aps[$ap_name]);
            echo "</pre>";
            //
            if (!empty($schools[$aps[$ap_name]['school_id']])) {
              echo 'School by Ap name: <br />';
              echo "<pre>";
              print_r($schools[$aps[$ap_name]['school_id']]);
              echo "</pre>";
            }
            else {
              echo 'School not found. <br />';
            }
          }
          else {
            echo 'AP not found by AP name. <br />';
          }
        }
      }
      else {
        $data = $AurbaControllers->getClientsFromController($controller_url, $school['controller_version']);
      }
      
      echo 'Data: <br />';
      if (!empty($data)) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        echo '<br />';
      }
      else {
        echo 'Didn\'t found anything in Controller.';
        echo '<br />';
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
    
    /**
     * Aruba Controller: Run Sync with forced DB update
     *
     * URL: /system/test/aruba-controllers-sync
     */
    public function getArubaControllersSync() {
      $school_id = !empty($_GET['school_id']) ? $_GET['school_id'] : NULL;
      \Artisan::call('aruba:sync:controllers', ['school_id' => $school_id, 'force' => 1]);
      return;
    }
    
    /**
     * Aruba Controller: Check All Controllers API
     *
     * URL: /system/test/aruba-controllers-check
     */
    public function getArubaControllersCheck() {
      $schools = School::whereNotNull('controller_url')->get()->toArray();
      if (!empty($schools)) {
        $schools = array_map_by_key($schools, 'id');
      }
      $AurbaControllers = new ArubaControllers();
      foreach ($schools as $s) {
        $data = $AurbaControllers->loginToArubaControllerOS8x($s['controller_url']);
        if (!empty($data)) {
          $os_8x_OK = true;
        }
        else {
          $os_8x_OK = false;
        }
        $data = $AurbaControllers->getClientFromControllerOS6x($s['controller_url'], ['ip' => '127.0.0.0']);
        if (!empty($data)) {
          $os_6x_OK = true;
        }
        else {
          $os_6x_OK = false;
        }
        echo $s['id'] . ' | ' . $s['name'], ' | ' ,$s['controller_url'], ' | ';
        echo 'Aruba OS 8.x API: ', ($os_8x_OK ? '<span style="color:green;">OK</span>' : '<span style="color:red;">NOT</span>');
        echo 'Aruba OS 6.x API: ', ($os_6x_OK ? '<span style="color:green;">OK</span>' : '<span style="color:red;">NOT</span>');
        echo '&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.open(\''. 'https://voks.afk.no//system/test/aruba-controllers-sync?school_id=' . $s['id'] .'\');return false;" value="Forced Sync" />';
        echo '<br />';
      }
    }
}
