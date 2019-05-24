
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    function adminApi($http, $timeout, $translate) {
        var Api = function() {
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
             */
            this.getStatus = function(){
                $http.get('/api/shelter/stats/' + config[ 'num-id' ]).success(function(data) {
                    self.processStatus(data);
                    $timeout(function () {
                        self.getStatus();
                    }, 3000);
                });
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
            this.processStatus = function(data){
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

            /**
             * Everything else
             */

            this.getDefaultFAQs = function () {
                return $http.get('/admin/help/list');
            };

            this.saveFaqItem = function (data) {
                return $http.post('/admin/help/save-faq-item', data);
            };

            this.removeFaqItem = function (data) {
                return $http.post('/admin/help/remove-faq-item', data);
            };

            this.saveFaqOrder = function (items) {
                return $http.post('/admin/help/save-faq-order', items);
            };

            this.toggleFaqVisibility = function (id, visible) {
                return $http.post('/admin/help/save-visibility', {id: id, visible: visible});
            };

            this.syncFaqItems = function () {
                return $http.get('/admin/help/reset');
            };

            this.getPushNotifications = function () {
                return $http.get('/admin/notifications/notification-list');
            };

            this.savePushNotification = function (data) {
                return $http.post('/admin/notifications/save-push-notification', data);
            };

            this.saveNotificationOrder = function (items) {
                return $http.post('/admin/notifications/save-notification-order', items);
            };

            this.removePushNotification = function (data) {
                return $http.post('/admin/notifications/remove-push-notification', data);
            };

            this.toggleNotificationVisibility = function (id, visible) {
                return $http.post('/admin/notifications/save-visibility', {id: id, visible: visible});
            };

            this.syncPushNotifications = function () {
                return $http.get('/admin/notifications/sync');
            };

            this.getButtons = function () {
                return $http.get('/admin/buttons/list');
            };

            this.saveButton = function (data) {
                return $http.post('/admin/buttons/save-button', data);
            };

            this.getLogs = function() {
                return $http.get('/admin/logs/list');
            };

            this.getReports = function() {
                return $http.get('/admin/reports/list');
            };

            this.removeEventLog = function(data) {
                return $http.post('/admin/logs/remove', data);
            };

            this.previewMap = function (floorId, buttonId) {
                window.open('/maps/preview/' + floorId + '/' + (buttonId ? buttonId : ''));
            };

            this.syncMaps = function () {
                return $http.get('/admin/maps/sync');
            };

            this.getMaps = function () {
                return $http.get('/admin/maps/list');
            };

            this.resetShelter = function(id) {
                return $http.get('/api/shelter/reset/' + id);
            };

            this.syncCrisisTeam = function() {
                return $http.get('/admin/crisis-team/sync');
            };
        };

        return new Api();
    }

    adminApi.$inject = ['$http', '$timeout', '$translate'];
    angular.module('admin').factory('AdminApi', adminApi);
})();