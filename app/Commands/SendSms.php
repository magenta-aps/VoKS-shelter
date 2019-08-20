<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use BComeSafe\Models\SchoolDefault;

/**
 * Class SendSms
 *
 * @package BComeSafe\Commands
 */
class SendSms extends Command implements SelfHandling, ShouldBeQueued
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
     * @param $clients
     * @param $message
     * @param $id
     */
    public function __construct($clients, $message)
    {
        $this->clients = $clients;
        $this->message = $message;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->clients)) return;

        $defaults = SchoolDefault::getDefaults();
        if (!empty($defaults->sms_provider)) {
          $integration = \Component::get('Sms')->getIntegration($defaults->sms_provider);
          foreach($this->clients as $client) {
            if (!empty($client['user_phone'])) {
              $integration->sendMessage($client['user_phone'], $this->message);
            }
          }
        }
    }
}
