
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mapConfigProvider = function() {
        /**
         * Holds configuration for different maps
         *
         * @private
         * @type {Object}
         */
        var _config = {
            default: {}
        };

        /**
         * Set default or specific map configuration
         *
         * @param {String|Object} name
         * @param {Object} options
         */
        var providerSetConfig = function(name, options) {
            if ('object' === typeof name) {
                options = name;
                name = 'default';
            }

            options = options || {};
            _config[name] = options;
        };

        /**
         * ---------------------------------------------------
         * MapConfig service
         * ---------------------------------------------------
         */

        /**
         * Service dependencies
         * Service constructor
         *
         * @type {Array}
         */
        var service = function() {

            // Auto initialize
            for (var key in _config) {
                if ('default' !== key) {
                    _config[key] = angular.merge({}, _config['default'], _config[key]);

                    var crs = _config[key].leaflet.crs;
                    _config[key].leaflet.crs = L.CRS[crs];
                }
            }

            /**
             * Get all map options by map name
             *
             * @param {String} name Map name
             */
            var serviceGetConfig = function(name) {
                name = name || 'default';
                return _config[name];
            };

            /**
             * MapConfig service
             * Public API
             *
             * @type {Object}
             */
            return {
                getConfig: serviceGetConfig
            };
        };
        service.$inject = [];

        /**
         * MapConfig provider
         * Public API
         *
         * @type {Object}
         */
        var provider = {
            $get: service,
            setConfig: providerSetConfig
        };
        return provider;
    };

    mapConfigProvider.$inject = [];
    angular.module('map.providers').provider('MapConfig', mapConfigProvider);
})();