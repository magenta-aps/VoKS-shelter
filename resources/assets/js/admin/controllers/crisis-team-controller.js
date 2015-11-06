/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var crisisTeamController = function($scope, Toast, AdminApi, $translate) {

        $scope.syncItems = function() {
            Toast.push('warning', $translate.instant('toast.contents.admin.team.sync'));
            AdminApi.syncCrisisTeam().success(function() {
                Toast.push('success', $translate.instant('toast.contents.admin.team.complete'));
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            });
        };
    };

    crisisTeamController.$inject = ['$scope', 'Toast', 'AdminApi', '$translate'];
    angular.module('admin').controller('CrisisTeamController', crisisTeamController);
})();