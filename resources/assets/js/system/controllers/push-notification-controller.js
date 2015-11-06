/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var pushNotificationController = function($scope, Toast, SystemApi, $translate) {
        SystemApi.getPushNotifications().success(function(list) {
            $scope.list = list;
        });

        angular.extend($scope, {
            list: [],
            inserted: {},
            saveItem: function(item, $index) {
                SystemApi.savePushNotification(item).success(function(data) {
                    Toast.push('success', $translate.instant('toast.contents.system.push.save_success'), '');
                    $scope.list[$index] = data;
                }).error(function() {
                    Toast.push('error', $translate.instant('toast.contents.system.push.save_error'), '');
                });
            },
            addItem: function() {
                $scope.inserted = {
                    id: 0,
                    label: '',
                    content: ''
                };
                $scope.list.push($scope.inserted);
            },
            removeItem: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }

                if (confirm($translate.instant('toast.contents.system.push.remove_message'))) {
                    SystemApi.removePushNotification({id: $id}).success(function() {
                        $scope.list.splice($index, 1);
                        Toast.push('success', $translate.instant('toast.contents.system.push.remove_success'), '');
                    });
                }
            },
            cancelEdit: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }
            },
            validateField: function(data) {
                if (data) {
                    var nospace = data.replace(/\s/g, '');
                    if (nospace === '') {
                        return $translate.instant('toast.contents.validation.required');
                    }
                } else {
                    return $translate.instant('toast.contents.validation.required');
                }
            }
        });
    };

    pushNotificationController.$inject = ['$scope', 'Toast', 'SystemApi', '$translate'];
    angular.module('system').controller('PushNotificationController', pushNotificationController);
})();