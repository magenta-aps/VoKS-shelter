<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\test\MainController;

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
use Devristo\Phpws\Client;
use React\EventLoop;
use Zend\Log;

/**
 * Class MainController
 *
 * @package  BComeSafe\Http\Controllers\System\Test
 */
class MainController extends BaseController
{

    public function getSettings()
    {
	phpinfo();
	return [];
    }

    public function getCoords()
    {
        \Artisan::call('aruba:sync:macs', ['mac:1' => 'B4:52:7E:3A:9E:B0', 'mac:2' => '84:38:38:4A:BB:C9']);
    }

    public function getUser()
    {
        $user = new User();
        return $user->getByIp('192.168.21.54');
    }

    public function getPush()
    {
        $item = new Notifications\Item('Got push?', isset($_GET['id']) ? $_GET['id'] : 1);
        $server = new Notifications\Server\Server($item);

        $server->registerDevicePusher(new Notifications\Pusher\Android(config('push.android')));
        //$ios = new Notifications\Pusher\iOS(config('push.ios'));
        //$server->registerDevicePusher($ios);
        //            $server->registerDevicePusher(new Notifications\Pusher\Desktop());

        $devices = [
        //    ['id' => '23d276d3a6fa4ef1f973a6d482057ee8abb3cfec6d16b4bcfe450031d0c116ee', 'type' => 'ios'],
    //        ['id' => '2bcaf885c662b7156e59bfd397cdf3587ef71f484e9ab5d292246347a56c2b68', 'type' => 'ios'],
    //        ['id' => '9a9395681fe67f7aacbf3133055fc8c91dbd3e478888265a77fa2df9955f83f6', 'type' => 'ios'],
        //                ['id' => '23d276d3a6fa4ef1f973a6d482057ee8abb3cfec6d16b4bcfe450031d0c116ee', 'type' => 'ios'],
    //        ['id' => 'd906cddee85040b13689756f75126b00dfba5ea54f1222f902dad4310324af97', 'type' => 'ios'],
    //        ['id' => '9a9395681fe67f7aacbf3133055fc8c91dbd3e478888265a77fa2df9955f83f6', 'type' => 'ios'],
        //                ['id' => '23d276d3a6fa4ef1f973a6d482057ee8abb3cfec6d16b4bcfe450031d0c116ee', 'type' => 'ios'],
          //Bjorn
          ['id' => 'APA91bHLkz1iBV27ZO9jl6x03ZCv8bn79wan2VNT1GN8b3CK5HCpxvVbewCKCPDh0YYBkNtZb4rHagsDvQOTrCrz5j1GekycGHHeWCqJdtAty1YuRch9txWM2P8R37kRLRIlFoQ81Ony', 'type' => 'android'],
          ['id' => 'APA91bGGGBeBt89LzKwSIphja0gnzKjDhE8-OCXW4wqswakp85I16bvntetKqECcpWIpiu2FSu2FnUWsg2p3eDJ6BJ1uveCV8xQfsoJMUGmpAgFp2vUPwKEIB2CEBdpPzLYsVc1g1IvK', 'type' => 'android'],
          ['id' => 'APA91bFAB32IUq610wKJcIg7oAlAFz0auaeEUWBagaZhbiZv75GyNF42fV9CGejGiAUzbX7pUw-zHt5NHToAYSew0xSlh56GfoEQbUyLgJMHJsMXNvupnWvbgqedDH19TuhLzaOrBgnP', 'type' => 'android'],
          //Tadas Samsung S7
          ['id' => 'APA91bEoT8YhJnxdia-GlDM1DvqnvxyYjcg4tKqlue9ghsTX_TvGOvNZOzgaJv6xHADJEBxG25BkSWjJ774L6J3T7OdXfeNnjILt87ur-zzXnitZqGsj3Z6NYFPZmMQ2Ga3xbZwFO1zE', 'type' => 'android'],
        ];

        print_r(config('push.android'));
        $response = $server->send($devices);

                   // dd($ios->getFeedback());

        return $response;
    }

    public function getCoordinateStats($full = 0)
    {
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

    public function getSms()
    {
        $curl = new CurlRequest();
        $phone_number = '+4795835235';
        $curl->setUrl(
            config('sms.talariax.url'),
            [
            'tar_num' => $phone_number,
            'tar_msg' => 'Hi! Did you got test SMS message?',
            'tar_mode' => config('sms.talariax.mode'),
            ]
        );
        print_r('URL: ' . config('sms.talariax.url'));
        echo '<br />';
        print_r('Phone number: ' . $phone_number);
        echo '<br />';
        echo 'Response: ';
        return $curl->execute();
    }

    public function getAle()
    {
        return Ale\Location::getCoordinates(@$_GET['mac']);
    }

    public function getWol()
    {
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
    public function getUcpTestMethods()
    {
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
