/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var avStreamFactory = function($q) {
        
	var AVStream = function() {
            var self = this;

            this.stream = null;

            this.clone = function() {
                var S = function() {
                    this.stream = self.stream.clone();
                };

                S.prototype.getStream = function() {
                    return this.stream;
                };

                S.prototype.pause = function() {
                    var tracks = this.stream.getAudioTracks();
                    for (var i in tracks) {
                        if (undefined !== tracks[i].enabled) {
                            tracks[i].enabled = false;
                        }
                    }
                    return this;
                };

                S.prototype.resume = function() {
                    var tracks = this.stream.getAudioTracks();
                    for (var i in tracks) {
                        if (undefined !== tracks[i].enabled) {
                            tracks[i].enabled = true;
                        }
                    }
                    return this;
                };

                return (new S()).pause();
            };

            this.getPermissions = function() {
                var defer = $q.defer();

                var constraints = {
                    video: false,
                    audio: true
                };

                var successCallback = function(MediaStream) {
                    self.stream = MediaStream;
                    defer.resolve(MediaStream);
                };

                var errorCallback = function(MediaStreamError) {
                    defer.reject(MediaStreamError);
                };

                navigator.getUserMedia(constraints, successCallback, errorCallback);

                return defer.promise;
            };
        };

        return new AVStream();
    };

    avStreamFactory.$inject = ['$q'];
    angular.module('app').factory('AVStream', avStreamFactory);
})();
