/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var resetController = function($location, ShelterAPI) {
        ShelterAPI
            .reset()
            .then(function() {
                localStorage.clear();
                $location.path('/stream').replace();
            });
    };

    resetController.$inject = ['$location', 'ShelterAPI'];
    angular.module('app.controllers').controller('ResetController', resetController);
})();