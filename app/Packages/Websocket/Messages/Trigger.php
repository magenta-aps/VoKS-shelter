<?php

namespace BComeSafe\Packages\Websocket\Messages;

/**
 * Class Trigger
 *
 * Generates a message for notifying shelter about alarm trigger
 *
 * @package BComeSafe\Packages\Websocket\Messages
 */
class Trigger extends BaseMessage implements MessageInterface
{

    /**
     * @var string
     */
    protected $messageType = 'ALARM_TRIGGERED';

    protected $destinationId;
    protected $sourceId;

    /**
     * @param $destinationId - school Id
     * @param $sourceId - device Id
     */
    public function __construct($destinationId, $sourceId)
    {
        $this->destinationId = (string) $destinationId;
        $this->sourceId = $sourceId;
    }

    /**
     * @return array
     */
    protected function formatMessage()
    {
        return [
            'dst' => $this->destinationId,
            'src' => $this->sourceId
        ];
    }
}
