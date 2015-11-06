/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var helpController = function($scope, Toast, $timeout, ShelterAPI) {
        $scope.help = {
            faq: [],
            files: []
        };

        $scope.status = ShelterAPI.status;

        ShelterAPI.getHelp().success(function(help) {
            $scope.help = help;
        });

        $scope.printFile = function(file) {
            ShelterAPI.printFile(file);
        };
    };

    helpController.$inject = ['$scope', 'Toast', '$timeout', 'ShelterAPI'];
    angular.module('app').controller('HelpController', helpController);
})();