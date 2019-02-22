<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 */

namespace BComeSafe\Packages\Cisco\Cmx\Importer;

/**
 * Class DataMapper
 *
 * @package  BComeSafe\Packages\Cisco\Cmx\Importer
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
            'campus_ale_id' => $structure['aesUidString'],
            'campus_hash_id' => $structure['aesUidString'],
            'campus_name' => $structure['name'],
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
            'building_ale_id' => $structure['aesUidString'],
            'building_name' => $structure['name']
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
            'floor_ale_id' => $structure['aesUidString'],
            'floor_name' => $structure['name'],
            'floor_number' => (int)!empty($structure['floorNumber']) ? $structure['floorNumber'] : 0,
            'floor_hash_id' => $structure['aesUidString'],
        ];

        return $floor;
    }

    /**
     * @param $baseUrl
     * @param $imagesUrl
     * @param $floor
     * @return array
     */
    public function mapImage($baseUrl, $imagesUrl, $floor)
    {
        $path = $baseUrl . $imagesUrl . '/' . $floor['image']['imageName'];
        $name = sha1($path) . '.' . pathinfo($path, PATHINFO_EXTENSION);

        $image = [
            'floor_id' => $floor['id'],
            'path' => $path,
            'file_name' => $name,
            'real_width' => $floor['dimension']['width'],
            'real_height' => $floor['dimension']['length'],
            'pixel_width' => $floor['image']['width'],
            'pixel_height' => $floor['image']['height']
        ];

        return compact('name', 'path', 'image');
    }

}
