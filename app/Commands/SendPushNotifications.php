<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Commands;

use BComeSafe\Packages\Notifications;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendPushNotifications
 *
 * @package BComeSafe\Commands
 */
class SendPushNotifications extends Command implements SelfHandling, ShouldBeQueued
{

    use InteractsWithQueue, SerializesModels;

    /**
     * @type
     */
    public $clients;
    /**
     * @type
     */
    public $message;
    /**
     * @type
     */
    public $notificationId;

    /**
     * @param $clients
     * @param $message
     * @param $id
     */
    public function __construct($clients, $message, $id)
    {
        $this->clients = $clients;
        $this->message = $message;
        $this->notificationId = $id;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $item = new Notifications\Item($this->message, $this->notificationId);
        $server = new Notifications\Server\Server($item);

        $server->registerDevicePusher(new Notifications\Pusher\Android(config('push.android')));
        $server->registerDevicePusher(new Notifications\Pusher\iOS(config('push.ios')));
        $server->registerDevicePusher(new Notifications\Pusher\Desktop());

        $response = $server->send($this->clients);
    }
}
