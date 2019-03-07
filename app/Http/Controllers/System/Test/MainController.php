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
use BComeSafe\Packages\Aruba\Airwave\Structure;
use BComeSafe\Packages\Aruba\Ale;
use BComeSafe\Packages\Aruba\Clearpass\Authentication;
use BComeSafe\Packages\Aruba\Clearpass\User;
use BComeSafe\Packages\Notifications;
use BComeSafe\Models\SchoolDefault;
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
        \Artisan::call('aruba:sync:macs', ['mac:1' => '<add mac address here>', 'mac:2' => '<add mac address here>']);
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
        
        if (!config('mail.from.address')) {
          echo 'From email address is empty.<br />';
        }
        else {
          echo 'From email address is: ' . config('mail.from.address') . '<br />';
        }
        
        if (!config('mail.from.name')) {
          echo 'From name is empty.<br />';
        }
        else {
          echo 'From name is: ' . config('mail.from.name') . '<br />';
        }
        
        $test_send = config('mail.test_send');
        echo 'Test send is: '.(!empty($test_send) ? 'Enabled' : 'Disabled').'.<br />';
        
        $test_email = config('mail.test_email');
        if (empty($test_email)) echo 'Test email is: '.(!empty($test_email) ? $test_email : 'Empty').'.<br />';
        
        $mail_driver = config('mail.driver');
        echo 'Mail driver:'.$mail_driver.'.<br />';
        
        //
        $emails = array();
        if (!empty($test_email) && !empty($test_send)) $emails[] = $test_email;
        
        $email_to = !empty($_GET['email']) ? $_GET['email'] : '';
        if (empty($email_to)) {
          echo 'Info: Missing <i>email<i> parameter.<br />';
        } 
        else {
          $emails[] = $email_to;
        }
        
        if (empty($emails)) {
          echo 'Error: Missing emails to send to.<br />';
          die('Stopped.');
        }

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

        echo 'Sending SMS. <br /><br />';
        $integration = \Component::get('Sms')->getIntegration($provider);
        $message = 'Hi!. This is a test message sent from BCS. Did you received?';
        $result = $integration->sendMessage($phone_number, $message, TRUE);
        echo 'Result:' . $result;
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
     * URL: /test/ucp-test-methods
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
}
