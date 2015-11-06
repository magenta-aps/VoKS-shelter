<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Aruba\Airwave\Importer;

use BComeSafe\Libraries\CurlRequest;
use BComeSafe\Models\Building;
use BComeSafe\Models\Campus;
use BComeSafe\Models\Floor;
use BComeSafe\Models\FloorImage;
use BComeSafe\Models\School;
use BComeSafe\Packages\Aruba\Ale\Location;
use SoapBox\Formatter\Formatter;

/**
 * Class Import
 *
 * @package BComeSafe\Packages\Aruba\Airwave\Importer
 */
class Import
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
            'baseUrl' => config('aruba.airwave.url'),
            'loginUrl' => config('aruba.airwave.login.url'),
            'loginData' => [
                'credential_0' => config('aruba.airwave.login.username'),
                'credential_1' => config('aruba.airwave.login.password'),
                'destination' => config('aruba.airwave.campuses.url'),
                'next_action' => config('aruba.airwave.campuses.url'),
            ],
            'schoolDefaults' => [
                'ip_address' => '127.0.0.1',
                'ad_id' => config('ad.default-group'),
                'locale' => \Lang::getLocale(),
                'mac_address' => '00:00:00:00:00:00'
            ],
            'cookiePath' => config('aruba.cookies.airwave'),
            'uploadDir' => public_path('uploads/maps/')
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
     * @return mixed
     * @throws \BComeSafe\Libraries\CurlRequestException
     */
    protected function pullData()
    {
        $data = (new CurlRequest())
            ->setUrl($this->options['loginUrl'])
            ->setCookieJar($this->options['cookiePath'])
            ->setPostRequest($this->options['loginData'])
            ->expect(
                CurlRequest::CUSTOM_RESPONSE,
                function ($response) {
                    return Formatter::make($response, Formatter::XML)->toArray();
                }
            )->execute();

        return $data;
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

        $floors = Location::getFloors();

        $data['campus'] = array_convert_to_numeric($data['campus']);

        //loop through campuses and add them to the database
        foreach ($data['campus'] as $campus) {
            $campus['model'] = Campus::import($this->mapper->mapCampus($campus));

            //create schools and sync them with campuses
            $school = $this->syncSchoolToCampus($campus['model']->id, $campus['model']->campus_name);

            //add campus and school IDs to the list of synced items
            $this->addImported('campuses', $campus['model']->campus_ale_id)
                ->addImported('schools', $school->id);

            if (!isset($campus['building'])) {
                continue;
            }

            $campus['building'] = array_convert_to_numeric($campus['building']);

            // import all buildings for the current campus iteration
            foreach ($campus['building'] as $building) {
                $building['model'] = Building::import($this->mapper->mapBuilding($school->id, $campus['model']->id, $building));

                $this->addImported('buildings', $building['model']->building_ale_id);

                if (!isset($building['site'])) {
                    continue;
                }

                $building['site'] = array_convert_to_numeric($building['site']);

                // import all floors for the current building iteration
                foreach ($building['site'] as $floor) {
                    // map Ale floor IDs to Airwave floor IDs
                    if (isset($floors[$floor['@attributes']['id']])) {
                        $floor['floor_hash_id'] = $floors[$floor['@attributes']['id']];
                    }

                    $floor['model'] = Floor::import($this->mapper->mapFloor($school->id, $building['model']->id, $floor));
                    $floor['id'] = $floor['model']->id;

                    $this->addImported('floors', $floor['model']->floor_ale_id);

                    // loop through images and only pick the original image since it's largest one
                    foreach ($floor['image'] as $image) {
                        if ($image['@attributes']['type'] === 'original') {
                            $mapped = $this->mapper->mapImage($this->options['baseUrl'], $floor, $image);

                            $this->downloadImage($mapped['name'], $mapped['path']);

                            $map = FloorImage::import(
                                $mapped['image'],
                                [
                                'floor_id' => $floor['model']->id,
                                ]
                            );

                            $this->addImported('floor_images', $map->file_name);

                            break;
                        }
                    }
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
            \DB::table($table)->whereNotIn($column, $this->imported[$table])->delete();
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
        $image = (new CurlRequest())
            ->setUrl($file)
            ->setCookieJar($this->options['cookiePath'])
            ->execute();

        \File::put($this->options['uploadDir'] . $name, $image);
    }
}
