/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var positionFilter = function() {
        return function(clients, index) {
            var client = {};
            for (var i in clients) {
                if (clients[i].position.index === index) {
                    client = clients[i];
                    break;
                }
            }

            return client;
        };
    };

    positionFilter.$inject = [];
    angular.module('app').filter('position', positionFilter);
})();