/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var buttonController = function($scope, Toast, AdminApi, $translate) {

        AdminApi.getButtons().success(function(data) {
            $scope.list = data;
        });

        angular.extend($scope, {
            list: [],
            inserted: {},
            saveItem: function(item, $index) {
                AdminApi.saveButton(item).success(function(data) {
                    Toast.push('success', $translate.instant('toast.contents.admin.button.save_success'), '');
                    data.old_floor_id = data.floor_id;
                    $scope.list[$index] = data;

                    for (var i in $scope.floors) {
                        if ($scope.floors[i].id === $scope.list[$index].floor_id) {
                            $scope.list[$index].floor = $scope.floors[i];
                            $scope.list[$index].old_floor_id = $scope.list[$index].floor_id;
                            break;
                        }
                    }
                }).error(function() {
                    Toast.push('error', $translate.instant('toast.contents.admin.button.save_error'), '');
                });
            },
            onFloorChange: function(button, floor) {
                button.old_floor_id = button.floor_id;
                button.floor_id = floor.id;
            },
            addItem: function() {
                $scope.inserted = {
                    id: 0,
                    button_name: '',
                    button_number: '',
                    mac_address: '',
                    ip_address: '',
                    floor_id: 0
                };

                $scope.list.push($scope.inserted);
            },
            removeItem: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }

                if (confirm('Do you really want to delete this button?')) {
                    AdminApi.removeButton({id: $id}).success(function() {
                        $scope.list.splice($index, 1);
                        Toast.push('success', $translate.instant('toast.contents.admin.button.remove_success'), '');
                    });
                }
            },
            cancelEdit: function($button, $index) {
                if (!$button.id) {
                    $scope.list.splice($index, 1);
                    return;
                }

                for (var i in $scope.floors) {
                    if ($scope.floors[i].id === $button.old_floor_id) {
                        $button.floor = $scope.floors[i];
                        $button.floor_id = $button.old_floor_id;
                        break;
                    }
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
            },
            previewButton: function(button) {
                AdminApi.previewMap(button.floor_id, button.id);
            }
        });
    };

    buttonController.$inject = ['$scope', 'Toast', 'AdminApi', '$translate'];
    angular.module('admin').controller('ButtonController', buttonController);
})();