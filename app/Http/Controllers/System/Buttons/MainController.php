<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\System\Buttons;

use BComeSafe\Http\Controllers\System\BaseController;
use BComeSafe\Http\Requests;
use BComeSafe\Models\Button;
use BComeSafe\Models\Floor;
use Illuminate\Http\Request;

/**
 * Class MainController
 *
 * @package  BComeSafe\Http\Controllers\System\Buttons
 */
class MainController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('system.buttons.index');
    }

    /**
     * @return array
     */
    public function getList()
    {
        $list = [];

        $list['list'] = Button::with('floor.building.campus')->get()->toArray();
        for ($i = 0; $i < count($list['list']); $i++) {
            $list['list'][$i]['floor'] = $this->formatFloor($list['list'][$i]['floor']);
        }

        $floors = Floor::with('building.campus')->get()->toArray();

        for ($i = 0; $i < count($floors); $i++) {
            $list['floors'][] = $this->formatFloor($floors[$i]);
        }

        return $list;
    }

    /**
     * @param $floor
     * @return array
     */
    protected function formatFloor($floor)
    {
        return [
            'id' => $floor['id'],
            'title' => join(
                ' - ',
                [
                $floor['building']['campus']['campus_name'],
                $floor['building']['building_name'],
                $floor['floor_name']
                ]
            )
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection|null|static
     */
    public function postRemoveButton(Request $request)
    {
        $item = Button::find($request->get('id'));

        if ($item) {
            $item->delete();
        }
        return $item;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection|null|static
     */
    public function postSaveButton(Request $request)
    {
        $data = $request->only([
            'id',
            'floor_id',
            'x',
            'y',
            'button_name',
            'mac_address',
            'ip_address',
            'button_number'
        ]);

        if ($data['id']) {
            $item = Button::find($data['id']);
            $item->update($data);
        } else {
            $item = Button::create($data);
        }

        return $item;
    }
}
