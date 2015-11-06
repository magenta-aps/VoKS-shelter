<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Maps;

use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Models\Button;
use BComeSafe\Models\Floor;
use BComeSafe\Packages\Aruba\Ale\Coordinates;

class MainController extends Controller
{
    public function __construct()
    {
    }

    public function getPreview($floorId, $buttonId = null)
    {

        $data = [
            'floor' => Floor::with('image')->find($floorId)
        ];

        if (empty($data['floor'])) {
            return view(
                'maps.preview',
                json_encode_values(
                    [
                    'floor' => [],
                    'buttons' => []
                    ]
                )
            );
        } else {
            $data['floor'] = $data['floor']->toArray();
        }

        if ($buttonId) {
            $data['buttons'] = Button::where('id', '=', $buttonId)->get()->toArray();
        } else {
            $data['buttons'] = Button::where('floor_id', '=', $floorId)->get()->toArray();
        }

        for ($i=0; $i<count($data['buttons']); $i++) {
            $data['buttons'][$i]['position'] = Coordinates::convert(
                $data['floor']['image']['pixel_width'],
                $data['floor']['image']['real_width'],
                $data['floor']['image']['pixel_height'],
                $data['floor']['image']['real_height'],
                $data['buttons'][$i]['x'],
                $data['buttons'][$i]['y']
            );
        }
        
        \View::share('config', \Shelter::getConfig());
        return view('maps.preview', json_encode_values($data));
    }
}
