<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Notifications\Pusher;

/**
 * Class iOS
 *
 * @package BComeSafe\Packages\Notifications\Pusher
 */
class iOS extends BasePusher
{

    /**
     * @type null
     */
    protected $connection = null;

    /**
     * @type string
     */
    protected $type = 'ios';

    /**
     * Format and send the message
     *
     * @param $text
     *
     * @return array
     * @throws \BComeSafe\Packages\Notifications\Pusher\iOSPusherException
     */
    public function processMessages($text)
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->options['certificate_path']);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->options['password']);

        $this->connection = stream_socket_client(
            $this->options['endpoint_url'],
            $error_code,
            $error_message,
            60,
            STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            $ctx
        );

        if ($this->connection === false) {
            throw new iOSPusherException($error_code . ' - ' . $error_message);
        }

        $count = count($this->devices);
        $message = $this->prepareMessage($text);

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $this->pushOut(['message' => $message, 'id' => $text['id'], 'device' => $this->devices[$i]]);
        }

        return $result;
    }

    /**
     * Format the message and encode into JSON
     *
     * @param $message
     *
     * @return string
     */
    public function prepareMessage($message)
    {
        $body['aps'] = [
            'content-available' => 1,
            'alert' => str_replace(["\r\n", "\n"], ' ', $message['message']),
            'id' => $message['id'],
            "timestamp" => round(microtime(true) * 1000),
            'category' => 'TAG_CATEGORY'
        ];

        return json_encode($body);
    }

    /**
     * Send out the formatted message through the socket
     *
     * @param $message
     *
     * @return int
     * @throws \BComeSafe\Packages\Notifications\Pusher\iOSPusherException
     */
    public function pushOut($message)
    {

        //pack the request into a binary string
        $msg = pack(
            'CNNnH*na*',
            1, // always one
            intval($message['id']), // sequential Id for a message
            time() + config('push.ios.expiry'), // UTC relative timestamp + one day
            32, // device token binary length
            str_replace(' ', '', $message['device']), // clean (no spaces, hex-only) device token
            mb_strlen($message['message'], '8bit'), // payload binary length
            $message['message']
        );

        $result = fwrite($this->connection, $msg, strlen($msg));

        if ($result === false) {
            throw new iOSPusherException('Could not send the payload.');
        }

        return $result;
    }

    /**
     * Close the connection after it's no longer needed
     */
    public function __destruct()
    {
        if ($this->connection) {
            @fclose($this->connection);
        }
    }

    public function getFeedback()
    {
        //            $ctx = stream_context_create();
        //            stream_context_set_option($ctx, 'ssl', 'local_cert', $this->options['certificate_path']);
        //            stream_context_set_option($ctx, 'ssl', 'passphrase', $this->options['password']);
        //
        //            $connection = stream_socket_client(
        //                'feedback.push.apple.com:2196', $error_code, $error_message, 60, STREAM_CLIENT_CONNECT, $ctx
        //            );
        //
        //            if ($connection === false) {
        ////                apns_close_connection($connection);
        ////
        //                echo("APNS Feedback Request Error: $error_code - $error_message");
        //            }
        //
        //            $feedback_tokens = [];
        //
        //            while (!feof($connection)) {
        //                $data = fread($connection, 38);
        //                if (strlen($data)) {
        //                    $feedback_tokens[] = unpack("N1timestamp/n1length/H*devtoken", $data);
        //                }
        //            }
        //
        //            fclose($connection);
        ////
        ////            if (count($feedback_tokens))
        ////                foreach ($feedback_tokens as $k => $token) {
        ////                    // code to delete record from database
        ////                }
        //
        //            return $feedback_tokens;

    }
}
