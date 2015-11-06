<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();
        
        if (empty($user) || $user->role !== 'super-admin') {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('home')->withErrors(
                    [
                    'username' => 'You are not authorized to access this section'
                    ]
                );
            }
        }

        return $next($request);
    }
}
