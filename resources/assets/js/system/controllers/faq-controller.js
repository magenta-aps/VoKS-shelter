/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var faqController = function($scope, Toast, SystemApi, $translate) {
        if (typeof initialToast !== 'undefined') {
            Toast.push('success', initialToast, '');
        }
        SystemApi.getDefaultFAQs().success(function(list) {
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
                SystemApi.saveFaqItem(item).success(function(data) {
                    Toast.push('success', $translate.instant('toast.contents.system.faq.save_success'), '');
                    $scope.list[$index] = data;
                }).error(function() {
                    Toast.push('error', $translate.instant('toast.contents.system.faq.save_error'), '');
                });
            },
            addItem: function() {
                $scope.inserted = {
                    id: 0,
                    question: '',
                    answer: '',
                    visible: true,
                    order: $scope.list.length
                };

                $scope.list.push($scope.inserted);
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

                SystemApi.saveOrder(order).success(function() {
                    Toast.push('success', $translate.instant('toast.contents.system.faq.order_success'), '');
                });
            },
            removeItem: function($id, $index) {
                if (!$id) {
                    $scope.list.splice($index, 1);
                }

                if (confirm($translate.instant('toast.system.faq.remove_message'))) {
                    SystemApi.removeFaqItem({id: $id}).success(function() {
                        $scope.list.splice($index, 1);
                        Toast.push('success', $translate.instant('toast.contents.system.faq.remove_success'), '');
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

    faqController.$inject = ['$scope', 'Toast', 'SystemApi', '$translate'];
    angular.module('system').controller('FaqController', faqController);
})();