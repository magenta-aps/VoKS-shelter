<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\PhoneSystem\Contracts;

/**
 * Integration contract interface
 */
interface IntegrationContract
{
    /**
     * Gets all available phone system nodes.
     *
     * @return array
     */
    public function getNodes();

    /**
     * Gets group list
     *
     * @param integer $nodeId Phone system node identifier
     *
     * @return array
     */
    public function getGroups($nodeId);

    /**
     * Gets all available voice recordings
     *
     * @param integer $nodeId Phone system node identifier
     *
     * @return array
     */
    public function getVoices($nodeId);

    /**
     * Plays the selected voice recording to all the group members.
     *
     * Interrupt parameter allows to specify additional voice recording that will play
     * to the other party of an interrupted call. 0 - no voice recording to be play.
     *
     * Specify repeat parameter to tell exactly the number of times the voice recording
     * must be played. 0 leaves it for the system to decide.
     *
     * @param integer $nodeId      Phone system node identifier
     *
     * @param integer $voiceId     Voice recording id
     * @param integer $groupId     Group identifier
     *
     * @param integer $interruptId Voice recording id to play when call gets interrupted
     * @param integer $repeat      Number of times to repeat the recording
     *
     * @return bool Success or error
     */
    public function play($nodeId, $voiceId, $groupId, $interruptId = 0, $repeat = 0);

    /**
     * Broadcast allows users to record a voice message on the spot and get it
     * played to the group members.
     *
     * Interrupt and repeat parameters work exactly as the above.
     *
     * Set live to true to get the message out to the group as the caller says it out loud.
     *
     * @param integer $nodeId      Phone system node identifier
     *
     * @param integer $phoneNumber Phone number
     * @param integer $groupId     Group identifier
     *
     * @param integer $interruptId Voice recording id to play when call gets interrupted
     * @param integer $repeat      Number of times to repeat the recording
     * @param boolean $live        Live broadcast or not
     *
     * @return bool Success or error
     */
    public function broadcast($nodeId, $phoneNumber, $groupId, $interruptId = 0, $repeat = 0, $live = false);
}
