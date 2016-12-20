<?php

namespace BComeSafe\Packages\Websocket\Messages;

/**
 * Class Profile
 *
 * Generates a message for client profile update on shelter
 *
 * @package BComeSafe\Packages\Websocket\Messages
 */
class IpWhitelist extends BaseMessage implements MessageInterface
{

    /**
     * @var string
     */
    protected $messageType = 'UPDATE_IP_WHITELIST';

    /**
     * User profile array
     *
     * @var
     */
    protected $list;

    /**
     * @param $list - list of ip addresses mapped to schoolIds
     */
    public function __construct($list)
    {
        $this->list = $list;
    }

    /**
     * @return array
     */
    protected function formatMessage()
    {
        return [
            'data' => $this->list
        ];
    }
}
