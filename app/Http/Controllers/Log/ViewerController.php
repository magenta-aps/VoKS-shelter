<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Log;

use BComeSafe\Http\Controllers\Controller;
use BComeSafe\Http\Requests;

/**
 * Class ViewerController
 *
 * @package  BComeSafe\Http\Controllers\Log
 */
class ViewerController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return array|\Illuminate\View\View
     */
    public function getWebsockets()
    {
        $file = file_get_contents(config('log.websocket.access'));
        $limit = config('log.limit', 100);

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (false === $file) {
            return [];
        }

        $lines = explode("\n", $file);
        $count = count($lines);

        $parsed = [];
        for ($i = $count - $limit; $i <= $count; $i++) {
            if (empty($lines[$i])) continue;

            preg_match('/\[(.*)\]\s\[(.*)\]\s+\[(.*)\](.*)/', $lines[$i], $matches);

            if (empty($matches)) continue;

            $parsed[] = [
                'timestamp' => $matches[1],
                'ram' => $matches[3],
                'message' => trim($matches[4])
            ];
        }

        if (isset($_GET['sort'])) {
            $parsed = array_reverse($parsed);
        }

        return view('log.websocket', ['lines' => $parsed]);
    }

    /**
     * @return mixed
     */
    public function getLaravel()
    {
        $controller = new \Rap2hpoutre\LaravelLogViewer\LogViewerController();
        return $controller->index();
    }
}
