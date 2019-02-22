<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 */

namespace BComeSafe\Packages\Cisco\Cmx\Importer;

use BComeSafe\Libraries\CurlRequest;
use BComeSafe\Models\Building;
use BComeSafe\Models\Campus;
use BComeSafe\Models\Floor;
use BComeSafe\Models\FloorImage;
use BComeSafe\Models\School;
use SoapBox\Formatter\Formatter;

/**
 * Class CmxImport
 *
 * @package BComeSafe\Packages\Cisco\Cmx\Importer
 */
class CmxImport
{
    /**
     * @var DataMapper
     */
    protected $mapper;

    /**
     * @var array
     */
    protected $importedMap = [
        'schools' => 'id',
        'campuses' => 'campus_ale_id',
        'buildings' => 'building_ale_id',
        'floors' => 'floor_ale_id',
        'floor_images' => 'file_name'
    ];

    /**
     * @var array
     */
    protected $imported = [];
    /**
     * @var array
     */
    protected $options = [];

    /**
     *
     */
    public function __construct()
    {
        $options = [
            'baseUrl' => config('cisco.baseUrl'),
            'campusesUrl' => config('cisco.api.campuses'),
            'imagesUrl' => config('cisco.api.images'),
            'schoolDefaults' => [
                'ip_address' => '127.0.0.1',
                'ad_id' => config('ad.default-group'),
                'locale' => \Lang::getLocale(),
                'mac_address' => '00:00:00:00:00'
            ],
            'uploadDir' => public_path('uploads/maps/'),
            'campusesList' => config('cisco.campusesList')
        ];

        $this->mapper = new DataMapper();

        $this->options = $options;
    }

    /**
     * @param $type
     * @param $id
     *
     * @return $this
     */
    protected function addImported($type, $id)
    {
        $this->imported[$type][] = $id;

        return $this;
    }

    /**
     * Get structure
     *
     * @param \Closure $callback
     *
     * @return array
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    protected function pullData(\Closure $callback = null)
    {
        $curl = new CurlRequest();
        $curl->setUrl($this->options['baseUrl'] . $this->options['campusesUrl']);
        $curl->setAuthentication(config('cisco.username'), config('cisco.password'));
        $curl->expect(CurlRequest::JSON_RESPONSE, $callback);
        $response = $curl->execute();

        return $response;
    }

    /**
     * @param $campusId
     * @param $campusName
     *
     * @return static
     */
    protected function syncSchoolToCampus($campusId, $campusName)
    {
        $school = School::where('campus_id', '=', $campusId)->first();

        if (!$school) {
            $school = School::create(
                array_merge(
                    [
                    'name' => $campusName,
                    'campus_id' => $campusId
                    ],
                    $this->options['schoolDefaults']
                )
            );

            Campus::find($campusId)->update(
                [
                'school_id' => $school->id
                ]
            );
        } else {
            $school->update(
                [
                'name' => $campusName,
                ]
            );
        }

        return $school;
    }

    /**
     * Imports Campus/Building/Floor structure from Airwave
     *
     * @return array $data - raw data from a request to ALE
     */
    public function structure()
    {
        $data = $this->pullData();
        if (empty($data['campuses'])) {
          return $data;
        }

        //If config has defined compuses names - import only them.
        $campuses_list = !empty($this->options['campusesList']) ? explode(',', $this->options['campusesList']) : array();

        //loop through campuses and add them to the database
        foreach ($data['campuses'] as $campus) {
            if (!empty($campuses_list) && !in_array($campus['name'], $campuses_list)) {
              continue;
            }
            $campus['model'] = Campus::import($this->mapper->mapCampus($campus));

            //create schools and sync them with campuses
            $school = $this->syncSchoolToCampus($campus['model']->id, $campus['model']->campus_name);

            //add campus and school IDs to the list of synced items
            $this->addImported('campuses', $campus['model']->campus_ale_id)
                ->addImported('schools', $school->id);

            if (empty($campus['buildingList'])) {
                continue;
            }

            // import all buildings for the current campus iteration
            foreach ($campus['buildingList'] as $building) {
                $building['model'] = Building::import($this->mapper->mapBuilding($school->id, $campus['model']->id, $building));

                $this->addImported('buildings', $building['model']->building_ale_id);

                if (empty($building['floorList'])) {
                  continue;
                }

                // import all floors for the current building iteration
                foreach ($building['floorList'] as $floor) {
                    $floor['model'] = Floor::import($this->mapper->mapFloor($school->id, $building['model']->id, $floor));
                    $floor['id'] = $floor['model']->id;

                    $this->addImported('floors', $floor['model']->floor_ale_id);

                    //Floor image
                    $mapped = $this->mapper->mapImage($this->options['baseUrl'], $this->options['imagesUrl'], $floor);
                    $this->downloadImage($mapped['name'], $mapped['path']);
                    $map = FloorImage::import(
                        $mapped['image'],
                        [
                        'floor_id' => $floor['model']->id,
                        ]
                    );
                    $this->addImported('floor_images', $map->file_name);
                }
            }
        }

        //clean up old records
        $this->cleanUp();

        return $data;
    }

    protected function cleanUp()
    {
        foreach ($this->importedMap as $table => $column) {
          if (isset($this->imported[$table])) {
            \DB::table($table)->whereNotIn($column, $this->imported[$table])->delete();
          }
        }
    }

    /**
     * @param $name
     * @param $file
     *
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    protected function downloadImage($name, $file)
    {

      $dest = $this->options['uploadDir'] . $name;
      echo `curl -k -b /tmp/cjar --output {$dest} {$file}`;
      //\File::put($this->options['uploadDir'] . $name, $image);
    }
}
