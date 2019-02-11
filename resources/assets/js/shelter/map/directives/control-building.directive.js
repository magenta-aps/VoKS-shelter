/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var buildingDirective = function(MapData) {
        var buildingDirectiveLink = function($scope, element, attributes, controllers) {
            var Map = controllers[0];

            $scope.getBuildings = function() {
                return MapData.getBuildings();
            };

            $scope.isBuilding = function(id) {
                return id === Map.state.building;
            };

            $scope.setBuilding = function(id) {
                Map.state.building = id;

                // Get the first floor of a building and
                // set it active
                var floors = MapData.getFloors(id),
                    floor = floors[0];

                Map.state.floor = floor.id;
            };
        };

        return {
            link: buildingDirectiveLink,
            replace: true,
            restrict: 'E',
            require: ['^map'],
            scope: false,
            templateUrl: '/views/control.building.directive.html'
        };
    };

    buildingDirective.$inject = ['MapData'];
    angular.module('map').directive('building', buildingDirective);
})();