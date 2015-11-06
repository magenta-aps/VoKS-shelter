<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Models\Button;
use Illuminate\Http\Request;

class ButtonController extends BaseController
{
    public function getIndex()
    {
        return view('admin.buttons.index');
    }

    public function getList()
    {
        $list = Button::getBySchoolId(\Shelter::getID());

        for ($i=0; $i<count($list); $i++) {
            $list[$i]->title = join(' - ', [$list[$i]->campus_name, $list[$i]->building_name, $list[$i]->floor_name]);
        }
        return $list;
    }

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

    public function postRemoveButton(Request $request)
    {
        $item = Button::find($request->get('id'));

        if ($item) {
            $item->delete();
        }
        return $item;
    }

    public function postSaveButton(Request $request)
    {
        $data = $request->only(['id', 'x', 'y']);

        if ($data['id']) {
            $item = Button::find($data['id']);
            $item->update($data);
        }

        return $item;
    }
}
