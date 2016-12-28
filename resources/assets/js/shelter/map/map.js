
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
        'map.providers',
        'map.services',
        'map.directives',
        'map.controllers'
    ];
    angular.module('map', dependencies);

    /**
     * Map module constants
     *
     * @constant {String} map.module
     */
    angular
        .module('map')
        .constant('templateDir', '/views/');

    /**
     * Map module config block dependencies
     *
     * @type {Array}
     */
    var mapModuleConfig = function() {};
    mapModuleConfig.$inject = [];

    /**
     * Map module run block dependencies
     *
     * @type {Array}
     */
    var mapModuleRun = function($q, $log, $http, $templateCache, templateDir) {
        /**
         * Load templates asynchronously
         *
         * @type {Array}
         */
        var templates = [
            'map.directive.html',
            'markers.directive.html',
            'marker.directive.html',
            'control.directive.html',
            'popup.html'
        ];

        // Promise array
        var promises = [];
        for (var index in templates) {
            if (undefined !== templates[index]) {
                var id = templates[index],
                    url = templateDir + id,
                    req = $http.get(url, {id: id});

                promises.push(req);
            }
        }

        var resolve = function(responses) {
            for (var index in responses) {
                if (undefined !== responses[index]) {
                    var response = responses[index];
                    $templateCache.put(response.config.id, response.data);
                }
            }
        };

        var reject = function(response) {
            $log.error(vsprintf('[map.js] Error %d: Template %s cannot be found.', [response.status, response.config.id]));
        };

        // Cache retrieved templates
        $q.all(promises).then(resolve, reject);

    };
    mapModuleRun.$inject = [
        '$q',
        '$log',
        '$http',
        '$templateCache',

        'templateDir'
    ];

    /**
     * Config & run
     */
    angular.module('map')
        .config(mapModuleConfig)
        .run(mapModuleRun);
})();
