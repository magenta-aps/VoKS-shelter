/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mainController = function($scope, $rootScope, $route, $timeout, SocketService, Client, State, AVStream, ShelterAPI, Connections) {

        $scope.clients = [];
        $scope.IDs = [];

        $scope.isHelp = function() {
            return 'help' === $route.current.active;
        };

        $scope.tab = 'streams';

        $scope.useGps = function()
        {
            return angular.isDefined(config['use-gps']) && config['use-gps'];
        };

        $scope.useNonGps = function()
        {
            return angular.isDefined(config['use-non-gps']) && config['use-non-gps'];
        };

        function getFirstClientIndex() {
            for (var i = 0; i < $scope.clients.length; i++) {
                if ($scope.clients[i].profile.type === 'client' && !$scope.clients[i].position.inQueue) {
                    return i;
                }
            }

            return false;
        }

        $scope.position = function(index) {
            return function(client) {
                return client.position.index === index;
            };
        };

        $scope.inLargeView = function() {
            return function(client) {
                return client.position.inLargeView === true;
            };
        };

        /**
         * Get AV permissions from the user
         */
        var permissionsSuccess = function() {
            ShelterAPI.getStatus().then(function(response) {
                var data = response.data;
                ShelterAPI.processStatus(data);
            });
            Connections.enableMicrophone();
        };

        

	var permissionsError = function() {
            ShelterAPI.getStatus().then(function(response) {
                var data = response.data;
                ShelterAPI.processStatus(data);
            });
            Connections.disableMicrophone();
        };

        var permissionExecute = function() {
            SocketService.connect();
            $scope.clients = Connections.getClients();
        };

        AVStream.getPermissions()
            .then(permissionsSuccess, permissionsError)
            .finally(permissionExecute);
    };

    mainController.$inject = [
        '$scope',
        '$rootScope',
        '$route',
        '$timeout',
        'SocketService',
        'Client',
        'State',
        'AVStream',
        'ShelterAPI',
        'Connections',
	
    ];
    angular.module('app').controller('MainController', mainController);
})();
