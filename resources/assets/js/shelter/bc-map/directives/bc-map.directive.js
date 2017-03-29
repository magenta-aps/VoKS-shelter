
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

	var intervalCycle = 3000;

    var bcMapDirective = function( $interval )
    {

        var bcMapDirectiveLink = function($scope, element, attributes, controllers)
        {
            var BcMap = controllers[0];
	        BcMap.createMap( element[0], function()
	        {
		        BcMap.createMarkers();
	        });

	        // Watch clients, and create markers
	        var createMarkers = function()
	        {
		        var map = BcMap.getMap();
		        if (null !== map)
		        {
			        BcMap.createMarkers();
		        }
	        };

	        $interval( createMarkers, intervalCycle, 0, false );
        };

        return {
            bindToController: true,
            controller: 'BcMap',
            controllerAs: 'bcMap',
            link: bcMapDirectiveLink,
            restrict: 'E',
            require: ['bc-map'],
            scope: {}
        };
    };

    bcMapDirective.$inject = ['$interval'];
    angular.module('bc-map.directives').directive('bcMap', bcMapDirective);
})();
