<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Middleware;

use BComeSafe\Models\Floor;
use Closure;

class DeviceApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // for testing purposes a client needs to have coordinates
        if ($request->get('test')) {
            $floor = Floor::where('school_id', '=', config('alarm.default_id'))->orderBy('id', 'desc')->get()->first();
            $request->query->remove('test');
            $request->query->add(
                [
                'x' => mt_rand(100, 500),
                'y' => mt_rand(100, 500),
                'floor_id' => $floor->id
                ]
            );
        }

        return $next($request);
    }
}
