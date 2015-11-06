
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mapDirective = function($compile, $templateCache) {
        // Compile and add container HTML
        var compileContainer = function($scope, parent) {
            var html = $templateCache.get('map.directive.html');
            var template = angular.element(html);

            $compile(template)($scope);
            parent.html(template);
        };

        // Compile and append control HTML
        var compileControl = function($scope, parent) {
            var html = '<control class="block-style -school-plan-block"></control>';
            var template = angular.element(html);

            $compile(template)($scope);
            parent.append(template);
        };

        var mapDirectiveLink = function($scope, element, attributes, controllers) {
            var Map = controllers[0];

            // Watch for floor change, compile and (re)create map
            var watchFloor = function() {
                return Map.state.floor;
            };

            var createMap = function(floorId) {
                if (null !== floorId) {
                    // Destroy map instance
                    var instance = Map.getMap();
                    if (null !== instance) {
                        Map.destroyMarkers();
                        Map.destroyMap();
                    }

                    // Compile and add map container & control
                    compileContainer($scope, element);
                    if (true === Map.options.control) {
                        compileControl($scope, element);
                    }

                    // Create map
                    var container = element.find('.map-container')[0];
                    Map.createMap(container);

                    // Create floor
                    Map.createFloor(floorId);

                    // Create markers
                    Map.createMarkers();
                }
            };

            $scope.$watch(watchFloor, createMap);

            // Watch clients, filter them and create markers
            var watchClients = function() {
                return angular.toJson(Map.clients);
            };

            var createMarkers = function() {
                var map = Map.getMap();
                if (null !== map) {
                    Map.createMarkers();
                }
            };

            $scope.$watch(watchClients, createMarkers);

            // Watch state pan property
            var watchPan = function() {
                return Map.state.pan;
            };

            var panMap = function() {
                if (null !== Map.state.pan) {
                    var macAddress = Map.state.pan;
                    Map.panMarker(macAddress);
                    Map.state.pan = null;
                }
            };

            $scope.$watch(watchPan, panMap);
        };

        return {
            bindToController: {
                id: '@'
            },
            controller: 'Map',
            controllerAs: 'Map',
            link: mapDirectiveLink,
            restrict: 'E',
            //replace: true,
            require: ['map'],
            scope: {}
            //templateUrl: '/js/map/templates/map.directive.html'
        };
    };

    mapDirective.$inject = ['$compile', '$templateCache'];
    angular.module('map.directives').directive('map', mapDirective);
})();