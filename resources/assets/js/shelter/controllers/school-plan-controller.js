/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var schoolPlanController = function($scope, $timeout, $translate, State, Toast, ShelterAPI, MapData, MapState) {
        $scope.currentMessageTab = 'push';

        /**
         * Push tab
         */
        $scope.push = {
            content: '',
            onContentChange: function() {
                var message = $scope.push.content;

                if (message.length > config['push-notification-limit']) {
                    $scope.push.content = message.substr(0, config['push-notification-limit']);
                }
            },
            template: null,
            templates: [],
            onTemplateChange: function($template) {
                $scope.push.content = angular.copy($template.content);
            },
            send: function(message) {
                var clients = $scope.clients.filter(function(client) {
                    return true === client.position.selected;
                });

                var send = [];
                for (var i = 0; i < clients.length; i++) {
                    send.push({
                        gcm_id: clients[i].profile.gcmId,
                        type: clients[i].profile.device
                    });
                }

                if (send.length === 0) {
                    Toast.push('error', $translate.instant('toast.title.error'), $translate.instant('toast.contents.school.push.select_client'));
                    return;
                }
                if (message.length === 0) {
                    Toast.push('error', $translate.instant('toast.title.error'), $translate.instant('toast.contents.school.push.no_message'));
                    return;
                }

                if (message.length > config['push-notification-limit']) {
                    Toast.push('error', $translate.instant('toast.title.error'), $translate.instant('toast.contents.school.push.too_long'));
                    return;
                }

                ShelterAPI.sendPushNotifications(send, message).success(function() {
                    Toast.push('success', $translate.instant('toast.title.message_sent'), $translate.instant('toast.contents.school.push.sent'));
                    $scope.push.template = null;
                    $scope.push.content = '';
                }).error(function() {
                    Toast.push('error', $translate.instant('toast.title.error'), $translate.instant('toast.contents.school.push.not_sent'));
                });
            },
            batchSelect: function($name) {
                var floor = MapState.getState('map', 'floor'),
                    floorData = MapData.getFloor(floor),
                    floors = MapData.getFloors(floorData.bid),
                    floorIds = [];

                // Find floors of the selected building
                for (var floorIndex in floors) {
                    if (undefined !== floors[floorIndex]) {
                        floorIds.push(floors[floorIndex].id);
                    }
                }

                for (var clientIndex in $scope.clients) {
                    if (undefined !== $scope.clients[clientIndex]) {
                        var client = $scope.clients[clientIndex];
                        client.position.selected = false;

                        if ('' !== client.profile.gcmId) {
                            switch ($name) {
                                case 'all':
                                    client.position.selected = true;
                                    break;
                                case 'floor':
                                    if (floor === client.position.floor) {
                                        client.position.selected = true;
                                    }
                                    break;
                                case 'building':
                                    if (-1 !== floorIds.indexOf(client.position.floor)) {
                                        client.position.selected = true;
                                    }
                                    break;
                                case 'none':
                                    client.position.selected = false;
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
        };

        ShelterAPI.getPushTemplates().success(function(templates) {
            $scope.push.templates = templates;
        });

        /**
         * History tab
         */
        $scope.history = [];
        var getHistory = function() {
            ShelterAPI.getHistory().success(function(history) {
                $scope.history = history;
                $timeout(getHistory, 2000);
            });
        };
        getHistory();

        /**
         * States
         * @deprecated ?
         */
        angular.extend($scope, {
            buildings: [],
            selectedBuilding: 0,
            selectBuilding: function($index) {
                $scope.selectedBuilding = $index;
                $scope.selectFloor(0);
            },
            selectedFloor: 0,
            selectedFloorId: 0,
            selectFloor: function($index) {
                $scope.selectedFloor = $index;
                $scope.selectedFloorId = $scope.buildings[$scope.selectedBuilding].floors[$scope.selectedFloor].floor_id;
                State.selected.map = $scope.buildings[$scope.selectedBuilding].floors[$scope.selectedFloor].image;
            },
            selection: true,
            action_move: true,
            action_selection: false,
            press_shift: false,
            toggleAction: function(move, drag) {
                $scope.action_move = move;
                $scope.action_selection = drag;
            }
        });

        /**
         * @deprecated ?
         */
        ShelterAPI.getMaps().success(function(buildings) {
            $scope.buildings = buildings;
            $scope.selectBuilding(0);
        });
    };

    schoolPlanController.$inject = [
        '$scope',
        '$timeout',
        '$translate',
        'State',
        'Toast',
        'ShelterAPI',
        'MapData',
        'MapState'
    ];
    angular.module('app').controller('SchoolPlanController', schoolPlanController);
})();