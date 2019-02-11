<?php

namespace BComeSafe\Packages\Websocket;
use BComeSafe\Packages\Websocket\Messages\IpWhitelist;

/**
 * Class ShelterClient
 *
 * Shelter specific websocket connection class
 *
 * @package BComeSafe\Packages\Websocket
 */
class ShelterClient extends Client
{

    /**
     * Send reset message
     *
     * @param $schoolId
     */
    public function reset($schoolId)
    {
        $this->sendMessage(new Messages\Reset($schoolId));
    }

    /**
     * Send trigger message
     *
     * @param $schoolId
     * @param $clientId
     */
    public function trigger($schoolId, $clientId)
    {
        $this->sendMessage(new Messages\Trigger($schoolId, $clientId));
    }

    /**
     * Sends updated stats for shelter
     *
     * @param $schoolId
     * @param $stats
     */
    public function update($schoolId, $stats)
    {
        $this->sendMessage(new Messages\Update($schoolId, $stats));
    }

    /**
     * Sends push notification messages to desktop apps
     *
     * @param $recipients
     * @param $message
     */
    public function pushMessages($recipients, $message)
    {
        $this->sendMessage(new Messages\Push($recipients, $message));
    }

    /**
     * Sends client profile to the websocket server
     * for caching
     *
     * @param $profile
     */
    public function profile($profile)
    {
        $this->sendMessage(new Messages\Profile($profile));
    }

    /**
     * Updates websockets with a list of school
     * IP addresses and IDs
     *
     * @param $whitelist
     */
    public function updateIpWhitelist($whitelist) {
        $this->sendMessage(new IpWhitelist($whitelist));
    }

    /**
     * Close the websocket connection on object destruction
     */
    public function __destruct()
    {
        $this->socket->close();
    }
}
