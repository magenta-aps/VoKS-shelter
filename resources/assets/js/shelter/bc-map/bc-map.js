
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    /**
     * Map module dependencies
     *
     * @type Array
     */
    var dependencies = [
	    'bc-map.directives',
        'bc-map.controllers'
    ];
    angular.module('bc-map', dependencies);

    /**
     * Map module config block dependencies
     *
     * @type {Array}
     */
    var bcMapModuleConfig = function() {};
    bcMapModuleConfig.$inject = [];

    /**
     * Map module run block dependencies
     *
     * @type {Array}
     */
    var bcMapModuleRun = function() {};
    bcMapModuleRun.$inject = [];

    /**
     * Config & run
     */
    angular.module('bc-map')
        .config(bcMapModuleConfig)
        .run(bcMapModuleRun);
})();
