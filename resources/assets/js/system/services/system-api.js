/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var systemApiFactory = function($http, $rootScope) {
        var Api = function() {
            var self = this;

            this.getDefaultFAQs = function() {
                return $http.get('/system/general/faqs');
            };

            this.saveFaqItem = function(data) {
                return $http.post('/system/general/save-faq-item', data);
            };

            this.removeFaqItem = function(data) {
                return $http.post('/system/general/remove-faq-item', data);
            };

            this.saveOrder = function(items) {
                return $http.post('/system/general/save-faq-order', items);
            };

            this.getPushNotifications = function() {
                return $http.get('/system/general/notification-list');
            };

            this.savePushNotification = function(data) {
                return $http.post('/system/general/save-push-notification', data);
            };

            this.removePushNotification = function(data) {
                return $http.post('/system/general/remove-push-notification', data);
            };

            this.getSchools = function() {
                return $http.get('/system/schools/list');
            };

            this.saveSchool = function(data) {
                return $http.post('/system/schools/save-school', data);
            };

            this.removeSchool = function(data) {
                return $http.post('/system/schools/remove-school', data);
            };

            this.getButtons = function() {
                return $http.get('/system/buttons/list');
            };

            this.saveButton = function(data) {
                return $http.post('/system/buttons/save-button', data);
            };

            this.removeButton = function(data) {
                return $http.post('/system/buttons/remove-button', data);
            };

            this.previewMap = function(floorId, buttonId) {
                window.open('/maps/preview/' + floorId + '/' + (buttonId ? buttonId : ''));
            };

            this.syncMaps = function() {
                return $http.get('/system/maps/sync');
            };

            this.getMaps = function() {
                return $http.get('/system/maps/list');
            };
        };

        return new Api();
    };

    systemApiFactory.$inject = ['$http', '$rootScope'];
    angular.module('system').factory('SystemApi', systemApiFactory);
})();