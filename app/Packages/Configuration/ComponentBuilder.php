<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Configuration;

/**
 * Class ComponentBuilder
 *
 * @package  BComeSafe\Packages\Configuration
 */
class ComponentBuilder
{

    /**
     * @var array
     */
    protected $registry = [];

    /**
     * @param $component
     * @return mixed
     * @throws ComponentDoesNotExistsException
     */
    public function get($component)
    {
        if (!isset($this->registry[$component])) {
            $class = '\\BComeSafe\\Packages\\'.$component.'\\'.$component.'ServiceManager';
            if (!class_exists($class)) {
                throw new ComponentDoesNotExistsException('Component '.$class . ' not found.');
            }

            $this->registry[$component] = new $class();
        }

        return $this->registry[$component];
    }
}
