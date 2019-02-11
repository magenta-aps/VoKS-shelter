/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var focusIfDirective = function($timeout) {
        return {
            restrict: 'A',
            link: function($scope, element, attributes) {
                $scope.$watch(attributes.focusIf, function(newVal) {
                    if (newVal) {
                        $timeout(function() {
                            element[0].focus();
                        });
                    }
                });
            }
        };
    };

    focusIfDirective.$inject = ['$timeout'];
    angular.module('app.directives').directive('focusIf', focusIfDirective);
})();