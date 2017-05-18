/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var schoolController = function($scope, Toast, SystemApi, $translate, $http, $q) {
        SystemApi.getSchools().success(function(list) {
            $scope.list = list;
        });

        $scope.model = {};
        $scope.model.phoneSystemIds = {};

        angular.extend($scope, {
            list: [],
            inserted: {},
            saveItem: function(item, $index) {
                SystemApi.saveSchool(item).success(function(data) {
                    Toast.push('success', $translate.instant('toast.contents.system.school.save_success'), '');
                    $scope.list[$index] = data;
                }).error(function() {
                    Toast.push('error', $translate.instant('toast.contents.system.school.save_error'), '');
                });
            },
            syncMaps: function() {
                Toast.push('warning', $translate.instant('toast.contents.system.school.sync'), '');

                SystemApi.syncMaps().success(function() {
                    Toast.push('success', $translate.instant('toast.contents.system.school.sync_success'), '');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                });
            },
            removeItem: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }

                if (confirm($translate.instant('toast.contents.system.school.remove_message'))) {
                    SystemApi.removeSchool({id: $id}).success(function() {
                        $scope.list.splice($index, 1);
                        Toast.push('success', $translate.instant('toast.contents.system.school.remove_success'), '');
                    });
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
            validateIp: function(id, ip) {
                var d = $q.defer();
                $http.post('/system/schools/validate-ip', {id: id, ip: ip}).success(function(res) {
                    res = res || {};
                    if (res === 'true') { // {status: "ok"}
                        d.resolve();
                    } else {
                        d.resolve(res);
                    }
                }).error(function(e) {
                    d.reject('Server error!');
                });
                return d.promise;
            },
            updatePhoneSystemIdList: function() {
                return $http.get('/api/ps/nodes', {})
                    .then(function(response) {
                        var data = response.data;
                        $scope.model.phoneSystemIds = data;
                    }, function(response) {
                    	/* silence is golden */
                    });
            },
            validatePhoneSystemId: function(id, data) {
                var defer = $q.defer();
                $http.post('/system/schools/phone-system-id-validate', {
                    id: id,
                    nodeId: data
                })
                    .then(function(response) {
                        var data = response.data;
                        if ('true' === data) {
                            defer.resolve();
                        } else {
                            defer.resolve(data);
                        }
                    }, function(response) {
                    	/* silence is golden */
	                    defer.resolve();
                    });

                return defer.promise;
            }
        });

        $scope.updatePhoneSystemIdList();
    };

    schoolController.$inject = ['$scope', 'Toast', 'SystemApi', '$translate', '$http', '$q'];
    angular.module('system').controller('SchoolController', schoolController);
})();