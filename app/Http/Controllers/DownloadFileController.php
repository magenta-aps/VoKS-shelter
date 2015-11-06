<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers;

use BComeSafe\Http\Requests;
use BComeSafe\Models\HelpFile;
use BComeSafe\Models\HelpFileDefault;

class DownloadFileController extends Controller
{

    public function getFaqFile($fileId = 'default', $police = 0)
    {

        if ($fileId === 'default') {
            $file = HelpFileDefault::first();
        } else {
            $file = HelpFile::find($fileId);
        }

        if (empty($file)) {
            return ['File not found.'];
        }

        if (!$police) {
            return response()->download($file->file_path, $file->name);
        } else {
            return response()->download($file->police_file_path, $file->police_name);
        }
    }
}
