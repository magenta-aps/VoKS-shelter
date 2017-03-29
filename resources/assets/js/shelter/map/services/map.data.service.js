
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mapData = function($http, $q, MapLog) {
        var _data = {
                buildings: [],
                floors: []
            };

        /**
         * Load map data
         *
         * @param {String} url
         *
         * @return {Promise} Parsed data promise
         */
        var load = function(url) {
            // Defer request
            var defer = $q.defer();

            // Request success
            var success = function(payload) {
                // Parse data & resolve or reject if empty
                var data = payload.data;
                if (0 < data.length && undefined !== data[0].floors) {
                    var parsed = _parse(data);
                    _data = parsed;
                    defer.resolve(parsed);
                } else {
                    var error = {
                        status: 204,
                        statusText: 'No content'
                    };

                    // MapLog.error(payload.status, payload.statusText, payload.config.url);
                    defer.reject(error);
                }
            };

            // Request error
            var error = function(payload) {
                // MapLog.error(payload.status, payload.statusText, payload.config.url);
                defer.reject();
            };

            $http.get(url).then(success, error);
            return defer.promise;
        };

        /**
         * Gets buildings
         *
         * @returns {Array}
         */
        var getBuildings = function() {
            return _data.buildings;
        };

        /**
         * Gets floors with data
         *
         * @param {String} buildingId Building identifier
         *
         * @returns {Array}
         */
        var getFloors = function(buildingId) {
            var floors = [];
            for (var index in _data.floors) {
                if(undefined !== _data.floors[index]) {
                    var floor = _data.floors[index];
                    if (buildingId === floor.bid) {
                        floors.push(floor);
                    }
                }
            }
            return floors;
        };

        /**
         * Gets floor data
         *
         * @param {String} floorId Floor identifier
         *
         * @return {Object|false} Floor data
         */
        var getFloor = function(floorId) {
            for (var index in _data.floors) {
                if (undefined !== _data.floors[index]) {
                    var floor = _data.floors[index];
                    if (floorId === floor.id) {
                        return floor;
                    }
                }
            }
            return false;
        };

        /**
         * Parse data
         *
         * @param {Object} Data
         * @return {Object} Parsed data
         */
        var _parse = function(data) {
            // Returned data
            var _data = {
                buildings: [],
                floors: []
            };

            // b(*) - building
            // f(*) - floor
            var bl = data.length;
            for (var bi = 0; bi < bl; bi++) {
                var b = data[bi],
                    fl = b.floors.length;

                _data.buildings.push({
                    id: b.id,
                    name: b.name
                });

                if (0 < fl) {
                    for (var fi = 0; fi < fl; fi++) {
                        var f = b.floors[fi];
                        _data.floors.push({
                            id: f.floor_id,
                            bid: b.id,
                            name: f.name,
                            image: f.image.path,
                            width: f.image.dimensions.width,
                            height: f.image.dimensions.height
                        });
                    }
                }
            }

            return _data;
        };

        /**
         * Map Data service
         * Public API
         */
        var service = {
            load: load,

            // building API
            getBuildings: getBuildings,

            // floor API
            getFloors: getFloors,
            getFloor: getFloor
        };
        return service;
    };

    mapData.$inject = ['$http', '$q', 'MapLog'];
    angular.module('map.services').service('MapData', mapData);
})();
