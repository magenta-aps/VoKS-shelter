<?php

namespace UCP\Api;

/**
 * Broadcast group API
 */
class BroadcastGroup extends AbstractApi
{
    /**
     * Gets group list
     *
     * ---------------------------------------------------------------
     * Method: BroadcastManager.getGroupsList
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

        $response = $this->post('BroadcastManager.getGroupsList', [$nodeId]);
        $data = json_decode($response->getBody()->getContents(), true);

        if (!array_key_exists('error', $data) && array_key_exists('result', $data)) {
            $data = $data['result'];
            foreach ($data as $list) {
                foreach ($list as $key => $item) {
                    $return[$key] = $item;
                }
            }
        }

        return $return;
    }
    
    /**
     * Gets group member list
     *
     * ---------------------------------------------------------------
     * Method: BroadcastManager.getGroupMembers
     * Params: nodeId, groupId
     * ---------------------------------------------------------------
     *
     * @param integer $nodeId      Phone system node identifier
     * @param integer $groupId     Group identifier
     * 
     * @return array
     */
    public function getMembers($nodeId, $groupId)
    {
        return 'NOT IMPLEMENTED';
    }
}
