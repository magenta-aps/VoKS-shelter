<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Configuration;

use Illuminate\Validation\Validator;

class ComponentValidator extends Validator
{
    public function validateComponent($attribute, $value, $parameters)
    {
        $components = array_change_key_case(\Component::get($parameters[0])->getIntegrations());
        return isset($components[strtolower($value)]);
    }
}
