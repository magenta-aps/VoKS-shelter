<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Notifications\Pusher;

/**
 * Class Android
 *
 * @package BComeSafe\Packages\Notifications\Pusher
 */
class Android extends BasePusher
{

    /**
     * @type string
     */
    protected $type = 'android';

    /**
     * @param $message
     *
     * @return array
     */
    public function prepareMessage($message)
    {
        $fields = [
            'registration_ids' => $this->devices,
            'data' => ["GCM_MESSAGE" => [
                "title" => "Crisis team message",
                "content" => str_replace(["\r\n", "\n"], ' ', $message['message']),
                "id" => $message['id'],
                "timestamp" => round(microtime(true) * 1000)
            ]]
        ];

        $headers = [
            'Authorization: key=' . $this->options['api_key'],
            'Content-Type: application/json'
        ];

        return ['fields' => $fields, 'headers' => $headers];
    }

    /**
     * @param $message
     *
     * @return mixed
     * @throws \BComeSafe\Packages\Notifications\Pusher\AndroidPusherException
     */
    public function pushOut($message)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->options['endpoint_url']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $message['headers']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message['fields']));

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new AndroidPusherException(curl_errno($ch) . ' - ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }
}
