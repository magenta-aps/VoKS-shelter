/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var reportsController = function($scope, Toast, AdminApi, $translate) {

        AdminApi.getReports().success(function(data) {
            $scope.list = data;
        });

        angular.extend($scope, {
            list: [],
            inserted: {}
        });
    };

    reportsController.$inject = ['$scope', 'Toast', 'AdminApi', '$translate'];
    angular.module('admin').controller('ReportsController', reportsController);
})();