<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers;

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

class TestController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * ignasb
     * yH8e@sR1
     *
     * rasas
     * K4g!Nw3f
     */

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
        return $user->getByIp('192.168.1.1');
    }

    public function getPush()
    {
        $item = new Notifications\Item('test push', isset($_GET['id']) ? $_GET['id'] : 1);
        $server = new Notifications\Server\Server($item);

        //            $server->registerDevicePusher(new Notifications\Pusher\Android(config('push.android')));
        $ios = new Notifications\Pusher\iOS(config('push.ios'));
        $server->registerDevicePusher($ios);
        //            $server->registerDevicePusher(new Notifications\Pusher\Desktop());

        $devices = [
            ['id' => 'deviceID', 'type' => 'ios'],
        ];

        $response = $server->send($devices);

        //            dd($ios->getFeedback());

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
        $phone_number = 'some wild phone number';
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
