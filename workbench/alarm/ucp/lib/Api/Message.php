<?php

namespace UCP\Api;

/**
 * Message API
 */
class Message extends AbstractApi
{
    /**
     * Sends text (SMS) message
     *
     * ---------------------------------------------------------------
     * Method: MessageManager.sendSMS
     * Params: phone, message
     * ---------------------------------------------------------------
     *
     * @param integer $phone       Phone number
     * @param string  $message     Message contents
     * 
     * @return bool Success or error
     */
    public function send($phone, $message)
    {
        $response = $this->post('MessageManager.sendSMS', [$phone, $message]);
        $data = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('result', $data)) {
            return true;
        }

        return false;
    }
}
