/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mapStateService = function() {
        /**
         * States by group map option
         *
         * @type {Object}
         */
        var state = {};

        /**
         * Create state groups
         *
         * @param {Array} groups
         */
        var serviceInitialize = function(groups) {
            for (var key in groups) {
                if (undefined !== groups[key]) {
                    var group = groups[key];
                    state[group] = {
                        building: null,
                        floor: null,
                        pan: null,
                        leaflet: {
                            bounds: null
                        }
                    };
                }
            }
        };

        /**
         * Get all states of a group
         *
         * @param {String} name
         *
         * @returns {Object}
         */
        var mapStateServiceGetStates = function(group) {
            if (undefined !== state[group]) {
                return state[group];
            }
            return false;
        };

        var mapStateServiceGetState = function(group, name) {
            if (undefined !== state[group] && undefined !== state[group][name]) {
                return state[group][name];
            }
            return false;
        };

        /**
         * MapState service
         * Public API
         *
         * @type {Object}
         */
        var service = {
            state: state,
            init: serviceInitialize,

            //
            getStates: mapStateServiceGetStates,
            getState: mapStateServiceGetState
        };
        return service;
    };

    mapStateService.$inject = [];
    angular.module('map.services').service('MapState', mapStateService);
})();