<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Aruba\Airwave\Importer;

/**
 * Class DataMapper
 *
 * @package  BComeSafe\Packages\Aruba\Airwave\Importer
 */
class DataMapper
{

    /**
     * @param $structure
     * @return array
     */
    public function mapCampus($structure)
    {
        $campus = [
            'campus_ale_id' => $structure['@attributes']['id'],
            'campus_hash_id' => strtoupper(str_replace('-', '', $structure['@attributes']['id'])),
            'campus_name' => $structure['@attributes']['name'],
        ];

        return $campus;
    }

    /**
     * @param $schoolId
     * @param $campusId
     * @param $structure
     * @return array
     */
    public function mapBuilding($schoolId, $campusId, $structure)
    {
        $building = [
            'school_id' => $schoolId,
            'campus_id' => $campusId,
            'building_ale_id' => $structure['@attributes']['id'],
            'building_name' => $structure['@attributes']['name']
        ];

        return $building;
    }

    /**
     * @param $schoolId
     * @param $buildingId
     * @param $structure
     * @return array
     */
    public function mapFloor($schoolId, $buildingId, $structure)
    {
        $floor = [
            'school_id' => $schoolId,
            'building_id' => $buildingId,
            'floor_ale_id' => $structure['@attributes']['id'],
            'floor_name' => $structure['@attributes']['name'],
            'floor_number' => (int)!empty($structure['@attributes']['floor']) ? $structure['@attributes']['floor'] : 0,
            'floor_hash_id' =>
                !isset($structure['floor_hash_id'])
                    ? strtoupper(str_replace('-', '', $structure['@attributes']['id']))
                    : $structure['floor_hash_id']
        ];

        return $floor;
    }

    /**
     * @param $baseUrl
     * @param $floor
     * @param $structure
     * @return array
     */
    public function mapImage($baseUrl, $floor, $structure)
    {
        $path = $baseUrl . str_replace('//', '/', trim($structure['relative-url']));
        $name = sha1($path) . '.' . pathinfo($path, PATHINFO_EXTENSION);

        $image = [
            'floor_id' => $floor['id'],
            'path' => $path,
            'file_name' => $name,
            'real_width' => $floor['@attributes']['width'],
            'real_height' => $floor['@attributes']['height'],
            'pixel_width' => $structure['pixel-width'],
            'pixel_height' => $structure['pixel-height']
        ];

        return compact('name', 'path', 'image');
    }

    /**
     * @param $floorId
     * @param $campusId
     * @param $structure
     * @return array
     */
    public function mapAccessPoint($floorId, $campusId, $structure)
    {
        $ap = [
            'floor_id' => $floorId,
            'campus_id' => $campusId,
            'ap_ale_id' => $structure['@attributes']['name']
        ];

        return $ap;
    }
}
