<?php

namespace BComeSafe\Packages\Websocket\Messages;

/**
 * Class PushMessage
 *
 * Generates a message for push notifications
 *
 * @package BComeSafe\Packages\Websocket\Messages
 */
class Push extends BaseMessage implements MessageInterface
{

    /**
     * @var string
     */
    protected $messageType = 'SEND_NOTIFICATION';

    protected $recipients;
    protected $stats;

    /**
     * @param $recipients
     * @param $payload
     */
    public function __construct($recipients, $payload)
    {
        $this->recipients = $recipients;
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    protected function formatMessage()
    {
        return [
            'receivers' => $this->recipients,
            'payload' => $this->payload
        ];
    }
}
