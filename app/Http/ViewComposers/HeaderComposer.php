<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $menu = [
            [
                'path' => 'stream',
                'class' => '-video',
                'title' => \Lang::get('header.menu.main.stream')
            ],
            [
                'path' => 'plan',
                'class' => '-location',
                'title' => \Lang::get('header.menu.main.plan')
            ],
            [
                'path' => 'help',
                'class' => '-help2',
                'title' => \Lang::get('header.menu.main.help')
            ]
        ];

        $view->with('menu', $menu);
    }
}
