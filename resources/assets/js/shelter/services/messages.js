/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var messagesFactory = function($rootScope) {
        var Messages = function() {
            var listKey = 'allMessages',
                clientKey = 'clientMessages';

            this.list = JSON.parse(localStorage.getItem(listKey)) || [];
            this.messages = JSON.parse(localStorage.getItem(clientKey)) || {};

            this.createMessage = function(options) {
                var timestamp = parseInt(options.timestamp, 10) || Date.now();

                var model = {
                    fromShelter: options.isShelter,
                    calledPolice: options.calledPolice,
                    popupOpen: false,
                    timestamp: timestamp,
                    message: options.message,
                    user: {
                        id: options.id,
                        name: options.name
                    }
                };

                return model;
            };

            /**
             * Removes specified message
             *
             * @param {Number} index Message index number
             */
            this.removeMessage = function(index) {
                this.list.splice(index, 1);
            };

            this.pushMessage = function(model) {
                if (!this.messages[model.user.id]) {
                    this.messages[model.user.id] = [];
                }

                this.messages[model.user.id].push(model);

                this.list.push(model);

                localStorage.setItem(listKey, angular.toJson(this.list));
                localStorage.setItem(clientKey, angular.toJson(this.messages));

                $rootScope.safeApply();

                return model;
            };

            this.save = function() {
                localStorage.setItem(listKey, angular.toJson(this.list));
                localStorage.setItem(clientKey, angular.toJson(this.messages));
                $rootScope.safeApply();
            };

            this.pushShelterMessage = function(model) {
                if (!this.messages[model.user.id]) {
                    this.messages[model.user.id] = [];
                }

                this.messages[model.user.id].push(model);

                localStorage.setItem(clientKey, angular.toJson(this.messages));

                $rootScope.safeApply();

                return model;
            };

            this.setPoliceStatus = function(clientId) {
                for (var i in this.list) {
                    if (clientId === this.list[i].user.id) {
                        this.list[i].calledPolice = true;
                    }
                }

                localStorage.setItem(listKey, angular.toJson(this.list));
            };

            this.unsetPoliceStatus = function(clientId) {
                for (var i in this.list) {
                    if (clientId === this.list[i].user.id) {
                        this.list[i].calledPolice = false;
                    }
                }

                localStorage.setItem(listKey, angular.toJson(this.list));
            };
        };

        return new Messages;
    };

    messagesFactory.$inject = ['$rootScope'];
    angular.module('app').factory('Messages', messagesFactory);
})();