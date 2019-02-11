<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Configuration\Contracts;

interface ExtractorContract
{
    public function setDirectory($directory);
    public function getDirectory();

    public function setNamespace($namespace);
    public function getNamespace();

    public function getIntegration($component);
    public function getIntegrations();
}
