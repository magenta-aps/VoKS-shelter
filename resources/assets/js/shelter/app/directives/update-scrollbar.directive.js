/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var updateScrollbar = function() {
        return {
            restrict: 'A',
            link: function($scope, element, attributes) {
                if ($scope.$last) {
                    element
                        .parents('[perfect-scrollbar]')
                        .first()
                        .perfectScrollbar('update');
                }
            }
        };
    };

    updateScrollbar.$inject = [];
    angular.module('app.directives').directive('updateScrollbar', updateScrollbar);
})();