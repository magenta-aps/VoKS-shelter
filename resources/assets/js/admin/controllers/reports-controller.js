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
            data.forEach(function(item) {
                item.false_alarm = !!+item.false_alarm; // coerce "1" to int and coerce int to boolean
            });
            $scope.list = data;
        });

        angular.extend($scope, {
            list: [],
            inserted: {},
            saveItem: function(item, $index) {
                AdminApi.saveReportItem(item).success(function(data) {
                    Toast.push('success', $translate.instant('toast.contents.admin.reports.save_success'), '');
                    $scope.list[$index].false_alarm = !!+data.false_alarm;
                    $scope.list[$index].note = data.note;
                }).error(function() {
                    Toast.push('error', $translate.instant('toast.contents.admin.reports.save_error'), '');
                });
            },
            removeItem: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }

                if (confirm($translate.instant('toast.contents.admin.reports.remove_message'))) {
                    AdminApi.removeReportItem({id: $id}).success(function() {
                        $scope.list.splice($index, 1);
                        Toast.push('success', $translate.instant('toast.contents.admin.reports.remove_success'), '');
                    });
                }
            },
        });
    };

    reportsController.$inject = ['$scope', 'Toast', 'AdminApi', '$translate'];
    angular.module('admin').controller('ReportsController', reportsController);
})();
