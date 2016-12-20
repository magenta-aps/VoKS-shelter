/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var streamDirective = function() {
        return {
            templateUrl: '/views/app-stream.html'
        };
    };

    streamDirective.$inject = [];
    angular.module('app.directives').directive('appStream', streamDirective);
})();