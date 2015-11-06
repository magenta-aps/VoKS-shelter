/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var controlDirective = function() {
        var controlDirectiveLink = function($scope, element, attributes, controllers) {
            var Map = controllers[0],
                map = Map.getMap();

            // Default tool
            var tool = 'move';

            // Zoom map in
            $scope.zoomIn = function() {
                map.zoomIn();
            };

            // Check if map is fully zoomed in
            $scope.isZoomedIn = function() {
                return map.getZoom() === map.getMaxZoom();
            };

            // Zoom map out
            $scope.zoomOut = function() {
                map.zoomOut();
            };

            // Check if map is fully zoomed out
            $scope.isZoomedOut = function() {
                return map.getZoom() === map.getMinZoom();
            };

            // Center map
            $scope.center = function() {
                var bounds = Map.state.leaflet.bounds;
                map.invalidateSize(false);
                map.fitBounds(bounds, {animate: true});
            };

            // Tool
            $scope.tool = function(name) {
                tool = name;
                switch (tool) {
                    case 'move':
                        map.dragging.enable();
                        map.selecting.disable();
                        break;
                    case 'select':
                        map.dragging.disable();
                        map.selecting.enable();
                        break;
                }
            };

            $scope.isTool = function(name) {
                return tool === name;
            };
        };

        return {
            link: controlDirectiveLink,
            restrict: 'E',
            require: ['^map'],
            scope: false,
            templateUrl: '/views/control.directive.html'
        };
    };

    controlDirective.$inject = [];
    angular.module('map.directives').directive('control', controlDirective);
})();