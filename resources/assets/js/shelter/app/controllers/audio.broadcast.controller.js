/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var audioBroadcastController = function($timeout, $translate, Toast, ShelterAPI) {
        var ctrl = this;

        // Models
        ctrl.number = null;
        ctrl.groupId = null;

        // Play button state
        ctrl.inactive = false;

        // Data
        ctrl.groups = [];

        // Get a list of groups
        ShelterAPI.broadcast().then(function(response) {
            var data = response.data;
            ctrl.groups = data.groups;
        });

        // Toast messages
        var success = {
                title: $translate.instant('audio.live.toast.success.title'),
                message: $translate.instant('audio.live.toast.success.message')
            },
            error = {
                playback: {
                    title: $translate.instant('audio.live.toast.error.playback.title'),
                    message: $translate.instant('audio.live.toast.error.playback.message')
                },
                invalid: {
                    title: $translate.instant('audio.live.toast.error.invalid.title'),
                    message: $translate.instant('audio.live.toast.error.invalid.message')
                }
            };

        // Play button click
        ctrl.play = function() {
            var number = ctrl.number,
                groupId = ctrl.groupId;

            // Invalid values, show error and exit
            if (null === number || null === groupId) {
                Toast.push('error', error.invalid.title, error.invalid.message);
                return;
            }

            // Make button inactive
            ctrl.inactive = true;

            // Play
            ShelterAPI.broadcast(number, groupId)
                .then(function(response) {
                    var data = response.data;

                    if (true === data.success) {
                        Toast.push('success', success.title, success.message);
                    } else {
                        Toast.push('error', error.playback.title, error.playback.message);
                    }

                    ctrl.inactive = false;
                }, function() {
                    Toast.push('error', error.playback.title, error.playback.message);
                    ctrl.inactive = false;
                });
        };
    };

    audioBroadcastController.$inject = ['$timeout', '$translate', 'Toast', 'ShelterAPI'];
    angular.module('app.controllers').controller('AudioBroadcastController', audioBroadcastController);
})();