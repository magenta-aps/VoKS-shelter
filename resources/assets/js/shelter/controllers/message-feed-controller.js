/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var messageFeedController = function($scope, Connections, Messages) {
        $scope.messages = Messages.list;

        $scope.users = {};
        $scope.streams = [];

        var watchClients = function() {
            return angular.toJson($scope.clients);
        };

        var updateUsers = function() {
            $scope.users = {};

            var clients = $scope.clients,
                length = clients.length;

            for (var index = 0; index < length; index++) {
                var client = clients[index];
                if ('client' === client.profile.type) {
                    $scope.users[client.profile.id] = client;
                }
            }
        };

        $scope.$watch(watchClients, updateUsers);

        $scope.togglePopup = function(message) {
            // toggle popup off
            if (message.popupOpen) {
                message.popupOpen = false;
                return;
            }

            // turn any other popup off
            for (var i in $scope.messages) {
                if (undefined !== $scope.messages[i]) {
                    $scope.messages[i].popupOpen = false;
                }
            }

            // turn popup on if such client exists
            var client = $scope.users[message.user.id];
            if (undefined !== client) {
                message.popupOpen = true;
            }
        };

        $scope.chat = function() {};
        $scope.listen = function() {};
        $scope.watch = function() {};
    };

    messageFeedController.$inject = ['$scope', 'Connections', 'Messages'];
    angular.module('app').controller('MessageFeedController', messageFeedController);
})();