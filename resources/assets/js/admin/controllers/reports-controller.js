/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var reportsController = function($scope, Toast, AdminApi, $translate) {
        $scope.searchFilter = { date: {startDate: null, endDate: null}, false_alarm: "all" };

        $scope.ranges = { // TODO $translate.instant('toast.reports.filters.date.ranges.last_7') etc.
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last year': [moment().subtract(1, 'years'), moment()]
        };

        $scope.dateOpts = {
            maxDate: moment(),
            ranges: $scope.ranges,
            autoApply: true
        };
        /*
        TODO translate datepicker using locale
         "locale": {
        "format": "MM/DD/YYYY",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
         */

        $scope.$watch('searchFilter', function(newFilter) {
            $scope.search(newFilter);
        }, true);

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
            search: function(filter) {
                AdminApi.getReports(filter).success(function(data) {
                    data.forEach(function(item) {
                        item.false_alarm = !!+item.false_alarm; // coerce "1" to int and coerce int to boolean
                    });
                    $scope.list = data;
                });
            },
            video_enabled: function() {
                return angular.isDefined(config['video-do-recording']) && config['video-do-recording'];
            }
        });

        $scope.search($scope.searchFilter);
    };

    reportsController.$inject = ['$scope', 'Toast', 'AdminApi', '$translate'];
    angular.module('admin').controller('ReportsController', reportsController);
})();
