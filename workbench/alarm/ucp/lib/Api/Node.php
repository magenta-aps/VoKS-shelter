<?php

namespace UCP\Api;

/**
 * Node API
 */
class Node extends AbstractApi
{
    /**
     * Gets a list of all available UCP nodes
     *
     * ---------------------------------------------------------------
     * Method: NodeManager.getNodes
     * ---------------------------------------------------------------
     *
     * @return array
     */
    public function getList()
    {
        $result = [];

        $response = $this->post('NodeManager.getNodes', []);
        $data = json_decode($response->getBody()->getContents(), true);

        if (!array_key_exists('error', $data) && array_key_exists('result', $data)) {
            $data = $data['result'];
            foreach ($data as $key => $value) {
                $result[$value['id']] = $value;
            }
        }

        return $result;
    }
}
