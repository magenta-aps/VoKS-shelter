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
            inserted: {},
            removeItem: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }

                if (confirm('Do you really want to delete this log item?')) {
                    AdminApi.removeEventLog({id: $id}).success(function() {
                        $scope.list.splice($index, 1);
                        Toast.push('success', $translate.instant('toast.contents.admin.logs.remove_success'), '');
                    });
                }
            }
        });
    };

    reportsController.$inject = ['$scope', 'Toast', 'AdminApi', '$translate'];
    angular.module('admin').controller('ReportsController', reportsController);
})();