/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var notificationController = function($scope, Toast, AdminApi, $translate) {
        AdminApi.getPushNotifications().success(function(list) {
            $scope.list = list;
        });

        var move = function(old_index, new_index) {
            if (new_index >= $scope.list.length) {
                var k = new_index - $scope.list.length;

                while ((k--) + 1) {
                    $scope.list.push(undefined);
                }
            }

            $scope.list.splice(new_index, 0, $scope.list.splice(old_index, 1)[0]);
        };

        angular.extend($scope, {
            list: [],
            inserted: {},
            saveItem: function(item, $index) {
                AdminApi.savePushNotification(item).success(function(data) {
                    Toast.push('success', $translate.instant('toast.contents.admin.push.save_success'), '');
                    $scope.list[$index] = data;
                }).error(function() {
                    Toast.push('error', $translate.instant('toast.contents.admin.push.save_error'), '');
                });
            },
            addItem: function() {
                $scope.inserted = {
                    id: 0,
                    label: '',
                    content: '',
                    visible: true,
                    order: $scope.list.length
                };

                $scope.list.push($scope.inserted);
            },
            removeItem: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }

                if (confirm($translate.instant('toast.contents.admin.push.remove_message'))) {
                    AdminApi.removePushNotification({id: $id}).success(function() {
                        $scope.list.splice($index, 1);
                        Toast.push('success', $translate.instant('toast.contents.admin.push.remove_success'), '');
                    });
                }
            },
            cancelEdit: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }
            },
            orderItem: function($item, $index, direction) {
                var newPosition = $index + (direction === 'up' ? -1 : 1);

                //make sure the new position doesn't go beyond limits
                if (newPosition === -1 || newPosition === $scope.list.length) {
                    return;
                }

                move($index, newPosition);

                var order = {};

                for (var i in $scope.list) {
                    if (undefined !== $scope.list[i]) {
                        order[i] = $scope.list[i].id;
                    }
                }

                AdminApi.saveNotificationOrder(order).success(function() {
                    Toast.push('success', $translate.instant('toast.contents.admin.push.order_success'), '');
                });
            },
            validateField: function(data, limit) {
                if (data) {
                    var nospace = data.replace(/\s/g, '');
                    if (nospace === '') {
                        return $translate.instant('toast.contents.validation.required');
                    }

                    if (limit < nospace.length) {
                        return $translate.instant('toast.contents.validation.max_char');
                    }
                } else {
                    return $translate.instant('toast.contents.validation.required');
                }
            },
            toggleVisibility: function(item, visible) {
                item.visible = visible;
                AdminApi.toggleNotificationVisibility(item.id, visible).success(function(data) {
                    item = data;
                    Toast.push('success', $translate.instant('toast.contents.admin.push.save_success'), '');
                });
            },
            syncItems: function() {
                if (!confirm($translate.instant('toast.contents.admin.push.import_message'))) {
                    return;
                }

                AdminApi.syncPushNotifications().success(function() {
                    Toast.push('success', $translate.instant('toast.contents.admin.push.import_success'), '');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                });
            }
        });
    };

    notificationController.$inject = ['$scope', 'Toast', 'AdminApi', '$translate'];
    angular.module('admin').controller('NotificationController', notificationController);
})();