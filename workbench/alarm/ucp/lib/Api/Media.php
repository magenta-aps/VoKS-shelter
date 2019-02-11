<?php

namespace UCP\Api;

/**
 * Media API
 */
class Media extends AbstractApi
{
    /**
     * Gets a list of all available voice recordings
     *
     * ---------------------------------------------------------------
     * Method: MediabankManager.getBroadcastVoicesList
     * Params: nodeId
     * ---------------------------------------------------------------
     *
     * @param integer $nodeId      Phone system node identifier
     * 
     * @return array
     */
    public function getList($nodeId)
    {
        $return = [];

        $response = $this->post('MediabankManager.getBroadcastVoicesList', [$nodeId]);
        $data = json_decode($response->getBody()->getContents(), true);

        if (!array_key_exists('error', $data) && array_key_exists('result', $data)) {
            $data = $data['result'];
            foreach ($data as $list) {
                foreach ($list as $key => $item) {
                    $return[$key] = str_replace('/broadcast/', '', $item);
                }
            }
        }

        return $return;
    }
}
