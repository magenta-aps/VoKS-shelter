<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Libraries;

/**
 * Class WakeOnLan
 *
 * @package  BComeSafe\Libraries
 */
class WakeOnLan
{
    /**
     * @var array
     */
    protected static $messages = [];

    /**
     * @return array
     */
    public static function getMessages()
    {
        return self::$messages;
    }

    /**
     * @param $addr
     * @param $mac
     * @param $socket_number
     * @return bool
     */
    private static function wakeOnLan($addr, $mac, $socket_number)
    {
        if (empty($addr) || empty($mac) || empty($socket_number)) {
            self::$messages[] = "Mac/Ip/Socket number required.";

            return false;
        }

        $addr_byte = explode(':', $mac);
        $hw_addr = '';

        for ($a = 0; $a < 6;
        $a++) {
            $hw_addr .= chr(hexdec($addr_byte[$a]));
        }
        $msg = chr(255) . chr(255) . chr(255) . chr(255) . chr(255) . chr(255);
        for ($a = 1; $a <= 16;
        $a++) {
            $msg .= $hw_addr;
        }

        // send it to the broadcast address using UDP
        // SQL_BROADCAST option isn't help!!
        $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($s == false) {
            self::$messages[] = "Error creating socket!";
            self::$messages[] = "Error code is '"
                . socket_last_error($s)
                . "' - "
                . socket_strerror(socket_last_error($s));

            return false;
        } else {
            // setting a broadcast option to socket:
            $opt_ret = socket_set_option($s, SOL_SOCKET, SO_BROADCAST, true);
            if ($opt_ret < 0) {
                self::$messages[] = "setsockopt() failed, error: " . strerror($opt_ret) . PHP_EOL;

                return false;
            }

            if (socket_sendto($s, $msg, strlen($msg), 0, $addr, $socket_number)) {
                self::$messages[] = "Magic Packet sent successfully!";
                socket_close($s);

                return true;
            } else {
                self::$messages[] = "Magic packet failed!";

                return false;
            }
        }
    }

    /**
     * @param $ip
     * @param $mac
     * @param int $socket_nr
     * @return bool
     */
    public static function sendMagicPacket($ip, $mac, $socket_nr = 7)
    {
        return WakeOnLan::wakeOnLan($ip, format_mac_address($mac), $socket_nr);
    }
}
