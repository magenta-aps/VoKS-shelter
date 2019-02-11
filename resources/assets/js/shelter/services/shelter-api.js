/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var shelterApi = function($http) {
        var Shelter = function() {
            var self = this;

            /**
             * Status
             *
             * @type {Object}
             */
            this.status = {
                alarm: {
                    // Alarm status:
                    // 0: Off
                    // 1: On
                    status: 0,

                    // Alarm started time
                    // - 0: Empty string
                    // - 1: Time
                    time: ''
                },
                police: {
                    // Angular model used by "Change police status":
                    // - off
                    // - on
                    called: 'off',

                    // Police callers counter
                    count: 0,

                    // List of users that called the police
                    callers: [],

                    // Police status:
                    // 0: Not called
                    // 1: Asked to call
                    // 2: Called
                    // 3: Cancelled
                    status: 0
                }
            };

            /**
             * Gets Shelter status
             *
             * @return {Promise}
             */
            this.getStatus = function() {
                return $http.get('/api/shelter/stats/' + config['num-id']);
            };

            /**
             * Update police status.
             * Triggers the alarm if necessary.
             *
             * @param {Number} status Police status
             */
            this.police = function(status) {
                $http
                    .post('/api/shelter/police/', {
                        'status': status
                    })
                    .then(function(response) {
                        var status = response.data;
                        self.processStatus(status);
                    });
            };

            /**
             * Processes Shelter status
             *
             * @param data {Object} Data to be processed
             * @param callback {Function} Callback function
             */
            this.processStatus = function(data) {
                // Temporary police status
                var police = 0;

                // Callers
                this.status.police.count = data.police_called;
                this.status.police.callers = data.callers;

                // Asked to call police
                if (0 < data.asked_to_call) {
                    police = 1;
                }

                // Called police, overwrites asked to call
                if (0 < data.police_called) {
                    police = 2;
                }

                // Set police status
                this.status.police.status = police;
                if (2 === police) {
                    this.status.police.called = 'on';
                } else {
                    this.status.police.called = 'off';
                }

                // Alarm status
                if (0 < data.status) {
                    this.status.alarm.status = 1;
                    this.status.alarm.time = data.time;
                }
            };

            this.getCoordinates = function(macs) {
                var endpoint = '/api/shelter/coordinates/';

                macs = macs || null;
                if (null !== macs) {
                    return $http.post(endpoint, {
                        list: macs
                    });
                } else {
                    return $http.get(endpoint);
                }
            };

            this.getUser = function(id) {
                return $http.get('/api/shelter/client/' + id);
            };

            this.getHistory = function() {
                return $http.get('/api/shelter/history/' + config['num-id']);
            };

            this.getHelp = function() {
                return $http.get('/api/shelter/help/' + config['num-id']);
            };

            this.getMaps = function() {
                return $http.get('/api/shelter/maps/' + config['num-id']);
            };

            this.getPushTemplates = function() {
                return $http.get('/api/shelter/push-templates/' + config['num-id']);
            };

            this.sendPushNotifications = function(clients, message) {
                return $http.post('/api/shelter/send-push', {
                    clients: clients,
                    message: message
                });
            };

            this.play = function(voiceId, groupId) {
                var endpoint = '/api/ps/play';
                voiceId = voiceId || null;
                groupId = groupId || null;

                if (null !== voiceId && null !== groupId) {
                    return $http.post(endpoint, {
                        voiceId: voiceId,
                        groupId: groupId
                    });
                } else {
                    return $http.get(endpoint);
                }
            };

            this.broadcast = function(number, groupId) {
                var endpoint = '/api/ps/broadcast';
                number = number || null;
                groupId = groupId || null;

                if (null !== number && null !== groupId) {
                    return $http.post(endpoint, {
                        number: number,
                        groupId: groupId
                    });
                } else {
                    return $http.get(endpoint);
                }
            };

            this.reset = function() {
                return $http.get('/api/shelter/reset/' + config['num-id']);
            };

            this.printFile = function(url) {
                var w = window.open(url);
                w.print();
            };

        };

        return new Shelter;
    };

    shelterApi.$inject = ['$http'];
    angular.module('app').factory('ShelterAPI', shelterApi);
})();