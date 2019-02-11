<?php

namespace BComeSafe\Packages\Websocket\Messages;

/**
 * Class Reset
 *
 * Generates a message for resetting the Shelter
 *
 * @package BComeSafe\Packages\Websocket\Messages
 */
class Reset extends BaseMessage implements MessageInterface
{

    /**
     * @var string
     */
    protected $messageType = 'RESET_SHELTER';

    /**
     * @param $destinationId - School Id
     */
    public function __construct($destinationId)
    {
        $this->destinationId = $destinationId;
    }

    /**
     * @return array
     */
    protected function formatMessage()
    {
        return [
            'dst' => $this->destinationId
        ];
    }
}
