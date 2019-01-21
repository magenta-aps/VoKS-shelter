/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var streamController = function($location, $route, State, Connections, MapState) {
        var vm = this;

        vm.minimize = function(clientId) {
            Connections.getClient(clientId).minimize();
            $location.path('/stream').replace();
        };

        vm.maximize = function(clientId) {
            if (null !== State.singleStream) {
                Connections.getClient(State.singleStream).minimize();
            }

            var client = Connections.getClient(clientId);
            if (client && client.position.inView) {
                client.maximize();
                $location.path('/stream/' + clientId).replace();
            } else {
                $location.path('/stream').replace();
            }
        };

        if ($route.current.params.clientId) {
            vm.maximize($route.current.params.clientId);
        }

        vm.isActive = function(clientId) {
            return clientId === State.selected.marker;
        };

        vm.setActive = function(clientId) {
            var client = Connections.getClient(clientId);

            if (undefined !== client.position.floor && undefined !== client.profile.mac_address) {
                // Get active route and map state group
                var route = $route.current.active,
                    state = ('plan' === route) ? 'map' : 'stream';

                // Change floor
                MapState.state[state].floor = client.position.floor;
                MapState.state[state].pan = client.profile.mac_address;

                State.selected.marker = client.profile.id;
            }
        };
        
        vm.setVideoStream = function(clientId) {
            var client = Connections.getClient(clientId);
            var videoTag = document.getElementById('streams_video-element_' + clientId);
            console.log(typeof(videoTag));
            console.log(videoTag);
            console.log(client);
            if (videoTag) {
                videoTag.srcObject = client.stream.object;
            }
        }
    };

    streamController.$inject = ['$location', '$route', 'State', 'Connections', 'MapState'];
    angular.module('app.controllers').controller('StreamController', streamController);
})();