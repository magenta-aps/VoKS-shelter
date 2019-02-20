<?php

namespace UCP\Api;

/**
 * Broadcast API
 *
 * CAUTION! THIS API DOES NOT FULLY IMPLEMENT AVAILABLE FEATURES AND USES
 * ONLY WHAT IS REQUIRED BY APP. CHANGE IN REQUIREMENTS MEANS CHANGES
 * IN THE CODE BELOW AS WELL.
 */
class Broadcast extends AbstractApi
{
    /**
     * Plays the selected voice recording to all the group members.
     *
     * Interrupt parameter allows to specify additional voice recording that will play
     * to the other party of an interrupted call. 0 - no voice recording to be play.
     *
     * Specify repeat parameter to tell exactly the number of times the voice recording
     * must be played. 0 leaves it for the system to decide.
     *
     * ---------------------------------------------------------------
     * Method: BroadcastManager.playVoice
     * Params: nodeId, groupId, voiceId, $interruptId
     * ---------------------------------------------------------------
     * Method: BroadcastManager.playRepeatingVoice
     * Params: nodeId, groupId, voiceId, repeat
     * ---------------------------------------------------------------
     *
     * @param integer $nodeId      Phone system node identifier
     *
     * @param integer $voiceId     Voice recording id
     * @param integer $groupId     Group identifier
     *
     * @param integer $interruptId Voice recording id to play when call gets interrupted
     * @param integer $repeat      Number of times to repeat the recording (for future use)
     *
     * @return bool Success or error
     */
    public function play($nodeId, $voiceId, $groupId, $interruptId = 0, $repeat = 0)
    {
        // Default params
        $method = 'BroadcastManager.playVoice';
        $params = [
            $nodeId,
            $groupId,
            $voiceId,
            $interruptId
        ];

        $response = $this->post($method, $params);
        $data = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('result', $data) && true === $data['result']) {
            return true;
        }

        return false;
    }
    
    /**
     * Broadcast allows users to record a voice message on the spot and get it
     * played to the group members.
     *
     * Interrupt and repeat parameters work exactly as the above.
     *
     * Set live to true to get the message out to the group as the caller says it out loud.
     *
     * ---------------------------------------------------------------
     * Method: BroadcastManager.broadcastVoice
     * Params: nodeId, groupId, phone
     * ---------------------------------------------------------------
     * Method: BroadcastManager.broadcastRepeatingVoice
     * Params: nodeId, groupId, phone, repeat
     * ---------------------------------------------------------------
     * Method: BroadcastManager.liveBroadcast
     * Params: nodeId, groupId, phone
     * ---------------------------------------------------------------
     *
     * @param integer $nodeId      Phone system node identifier
     *
     * @param integer $phoneNumber Phone number
     * @param integer $groupId     Group identifier
     *
     * @param integer $interruptId Voice recording id to play when call gets interrupted (for future use)
     * @param integer $repeat      Number of times to repeat the recording (for future use)
     * @param boolean $live        Live broadcast or not (for future use)
     * 
     * @return bool Success or error
     */
    public function broadcast($nodeId, $phoneNumber, $groupId, $interruptId = 0, $repeat = 0, $live = false)
    {
        // Default params
        $method = 'BroadcastManager.liveBroadcast';
        $params = [
            $nodeId,
            $groupId,
            $phoneNumber
        ];

        $response = $this->post($method, $params);
        $data = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('result', $data) && true === $data['result']) {
            return true;
        }

        return false;
    }
}
