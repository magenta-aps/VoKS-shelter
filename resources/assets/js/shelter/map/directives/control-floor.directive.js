/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var floorDirective = function(MapData) {
        var floorDirectiveLink = function($scope, element, attributes, controllers) {
            var Map = controllers[0];

            $scope.getFloors = function() {
                var buildingId = Map.state.building;
                return MapData.getFloors(buildingId);
            };

            $scope.isFloor = function(floorId) {
                return floorId === Map.state.floor;
            };

            $scope.setFloor = function(floorId) {
                Map.state.floor = floorId;
            };
        };

        return {
            link: floorDirectiveLink,
            replace: true,
            restrict: 'E',
            require: ['^map'],
            scope: false,
            templateUrl: '/views/control.floor.directive.html'
        };
    };

    floorDirective.$inject = ['MapData'];
    angular.module('map').directive('floor', floorDirective);
})();