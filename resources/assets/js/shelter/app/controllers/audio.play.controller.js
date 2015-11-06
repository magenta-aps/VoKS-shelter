
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var audioPlayController = function($timeout, $translate, Toast, ShelterAPI) {
        var ctrl = this;

        // Models
        ctrl.voiceId = null;
        ctrl.groupId = null;

        // Play button state
        ctrl.inactive = false;

        // Data
        ctrl.voices = [];
        ctrl.groups = [];

        // Get a list of voices and groups
        ShelterAPI.play().then(function(response) {
            var data = response.data;
            ctrl.voices = data.voices;
            ctrl.groups = data.groups;
        });

        // Toast messages
        var success = {
                title: $translate.instant('audio.play.toast.success.title'),
                message: $translate.instant('audio.play.toast.success.message')
            },
            error = {
                playback: {
                    title: $translate.instant('audio.play.toast.error.playback.title'),
                    message: $translate.instant('audio.play.toast.error.playback.message')
                },
                invalid: {
                    title: $translate.instant('audio.play.toast.error.invalid.title'),
                    message: $translate.instant('audio.play.toast.error.invalid.message')
                }
            };

        // Play button click
        ctrl.play = function() {
            var voiceId = ctrl.voiceId,
                groupId = ctrl.groupId;

            // Invalid values, show error and exit
            if (null === voiceId || null === groupId) {
                Toast.push('error', error.invalid.title, error.invalid.message);
                return;
            }

            // Make button inactive
            ctrl.inactive = true;

            // Play
            ShelterAPI.play(voiceId, groupId)
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

    audioPlayController.$inject = ['$timeout', '$translate', 'Toast', 'ShelterAPI'];
    angular.module('app.controllers').controller('AudioPlayController', audioPlayController);
})();