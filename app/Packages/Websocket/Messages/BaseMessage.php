<?php

namespace BComeSafe\Packages\Websocket\Messages;

abstract class BaseMessage
{

    /**
     * Value that goes into the type index
     *
     * @var
     */
    protected $messageType;

    /**
     * Message getter
     *
     * @return mixed
     */
    public function getMessage()
    {
        $message = $this->formatMessage();
        $message['type'] = $this->messageType;

        return json_encode($message);
    }

    /**
     * Formats the message array
     *
     * @return array
     */
    abstract protected function formatMessage();
}
