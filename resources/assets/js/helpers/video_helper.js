/**
 * Service to start and stop Screen Capture.
 * Sends requests to a Go app that records screen on alarm trigger
 * Can be enabled in .env with "video_do_recording = TRUE"
 */

(function () {
    'use strict';

    var videoFactory = function ($http, Toast, $translate) {
        var Recorder = function () {

            this.url = config['video-base-url'];

            this.startRecording = function () {
                var start_url = this.url + "start";
                $http.get(start_url);
            };


            this.stopRecording = function (name) {
                var stop_url = this.url + "stop";
                var data = {name: name || "", reset: "true"};

                $http.post(stop_url, JSON.stringify(data))
                    .success(function (res) {
                        var filename = res['filename'];
                        Toast.push('success', $translate.instant('toast.contents.reset.video.success'));
                        return filename;
                    })
                    .error(function () {
                        Toast.push('error', $translate.instant('toast.contents.reset.video.error'));
                        return null;
                    });
            };


            this.startStatusPing = function () {
                this.getStatus(this.url);

                setInterval(this.getStatus, 10000, this.url);
            };


            this.getStatus = function (url) {
                $http.get(url + "status")
                    .success(function (res) {
                        if (res['Status'] === 2) {
                            var name = prompt($translate.instant('toast.contents.reset.video.prompt'));
                            var data = {name: name || "", reset: "false"};
                            $http.post(url + "stop", JSON.stringify(data))
                                .success(function () {
                                    Toast.push('success', $translate.instant('toast.contents.reset.video.success'));
                                })
                                .error(function () {
                                    Toast.push('error', $translate.instant('toast.contents.reset.video.error'));
                                });
                        }
                    });
            };
        };

        return new Recorder();
    };

    videoFactory.$inject = ['$http', 'Toast', '$translate'];
    angular.module('recorders', []).factory('Recorder', videoFactory);
})();
