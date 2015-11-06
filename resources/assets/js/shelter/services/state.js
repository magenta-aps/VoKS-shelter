/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var stateFactory = function() {
        var diff = function(base, comparison) {
            return base.filter(function(i) {
                return comparison.indexOf(i) < 0;
            });
        };

        var State = function() {
            /**
             * Number of streams that can be placed into view
             *
             * @type {number}
             */
            this.allowedStreamCount = 7;
            this.allowedListeningCount = 10;

            this.hadStreams = false;

            /**
             * Stores taken stream positions
             *
             * @type {Array}
             */
            this.takenStreamPositions = [];

            this.singleStream = null;

            this.selected = {
                pin: null,
                map: null,
                marker: null
            };

            /**
             * Open single stream view
             *
             */
            this.openSingleStream = function(id) {
                this.singleStream = id;
            };

            /**
             * Close single stream view
             *
             */
            this.closeSingleStream = function() {
                this.singleStream = null;
            };

            /**
             * Checks if there's a spot in the stream view
             *
             * @returns {boolean}
             */
            this.streamsHaveRoom = function() {
                if (this.takenStreamPositions.length < this.allowedStreamCount) {
                    return true;
                }

                return false;
            };

            /**
             * Get an empty spot for a stream
             *
             * @returns {boolean}
             */
            this.getFreeStreamPosition = function() {
                var free = diff([1, 2, 3, 4, 5, 6, 7], this.takenStreamPositions);

                if (free) {
                    return free[0];
                }

                return false;
            };

            this.isFirstStream = function() {
                return this.takenStreamPositions.length === 1;
            };

            /**
             * Remove stream record from global state
             *
             * @param position
             */
            this.removeStream = function(position) {
                var index = this.takenStreamPositions.indexOf(position);
                if (index > -1) {
                    this.takenStreamPositions.splice(index, 1);
                }
            };

            /**
             * Add stream record in global state
             *
             * @param position
             */
            this.addStream = function(position) {
                if (position !== 0) {
                    this.takenStreamPositions.push(position);
                }
            };

            /**
             *
             * @param position
             * @returns {boolean}
             */
            this.availablePosition = function(position) {
                if (0 === position) {
                    return false;
                }

                for (var i in this.takenStreamPositions) {
                    if (this.takenStreamPositions[i] === position) {
                        return false;
                    }
                }

                return true;
            };

            this.incrementListening = function() {
                this.allowedListeningCount++;
            };

            this.incrementListening = function() {
                this.allowedListeningCount--;
            };

            window.state = this;
        };

        return new State();
    };

    stateFactory.$inject = [];
    angular.module('app').factory('State', stateFactory);
})();