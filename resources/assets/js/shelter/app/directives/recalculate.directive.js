/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

/**
 * Recalculate dependencies
 * Recalculate directive
 *
 * @type {Array}
 */
(function() {
    'use strict';

    var recalculateDirective = function() {
        return {
            restrict: 'A',
            link: function($scope, element) {
                var streamsLarge = $('#stream-view > .streams__large, #school-view > .streams__large', element);
                if (0 < streamsLarge.length) {
                    var streamsSmall = $('.streams__small > .streams__small', streamsLarge);
                    $('.streams__main, .streams__main > map, .streams__main > map > .map-container')
                        .height(streamsLarge.height() - streamsSmall.height() - 8);
                }
            }
        };
    };

    recalculateDirective.$inject = [];
    angular.module('app.directives').directive('recalculate', recalculateDirective);
})();