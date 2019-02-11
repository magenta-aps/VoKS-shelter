<?php

namespace BComeSafe\Packages\Websocket\Messages;

/**
 * Class Update
 *
 * Generates a message for stats update
 *
 * @package BComeSafe\Packages\Websocket\Messages
 */
class Update extends BaseMessage implements MessageInterface
{

    /**
     * @var string
     */
    protected $messageType = 'SHELTER_UPDATE';

    protected $destinationId;
    protected $stats;

    /**
     * @param $destinationId - school Id
     * @param $stats - shelter stats
     */
    public function __construct($destinationId, $stats)
    {
        $this->destinationId = $destinationId;
        $this->stats = $stats;
    }

    /**
     * @return array
     */
    protected function formatMessage()
    {
        return [
            'dst' => $this->destinationId,
            'data' => $this->stats
        ];
    }
}
