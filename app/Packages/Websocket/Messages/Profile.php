<?php

namespace BComeSafe\Packages\Websocket\Messages;

/**
 * Class Profile
 *
 * Generates a message for client profile update on shelter
 *
 * @package BComeSafe\Packages\Websocket\Messages
 */
class Profile extends BaseMessage implements MessageInterface
{

    /**
     * @var string
     */
    protected $messageType = 'PROFILE_UPDATE';

    /**
     * User profile array
     *
     * @var
     */
    protected $profile;

    /**
     * @param $profile - user profile
     */
    public function __construct($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return array
     */
    protected function formatMessage()
    {
        return [
            'data' => $this->profile
        ];
    }
}
