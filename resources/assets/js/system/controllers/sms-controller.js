/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var smsController = function($scope, Toast, SystemApi) {
        /**
         * Predefined text that comes from HTML
         */
        $scope.model = model;

        /**
         * Max. SMS characters
         *
         * @type {number}
         */
        $scope.max = 160;

        /**
         * Counts SMS message characters including line breaks
         *
         * @param {string} message
         *
         * @return {number} Character count
         */
        var count = function(message) {
            return message.length + (message.match(/\n/g) || []).length;
        };

        /**
         * Length counter
         *
         * @param {string} message SMS text message
         *
         * @returns {number} Character count
         */
        $scope.counter = function(message) {
            return $scope.max - count(message);
        };
    };

    smsController.$inject = ['$scope', 'Toast', 'SystemApi'];
    angular.module('system').controller('SmsController', smsController);
})();