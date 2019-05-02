
(function() {
    'use strict';

    var videoFactory = function() {
        var Recorder = function() {
 
	    this.url = "https://loc.bcomesafe.com:3032/";
            this.recording = 0;

            this.startRecording = function() {
                var start_url = this.url + "start";
                var xhr = new XMLHttpRequest();
                xhr.open("GET", start_url, true);
                xhr.send();

                this.recording = 1;
            };


	     this.stopRecording = function(name) {
                var stop_url = this.url + "stop";
                var data = {name: name || ""};
                var xhr = new XMLHttpRequest();
                xhr.open("POST", stop_url, true);
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            Toast.push('success', $translate.instant('toast.contents.reset.video.success'));
                        } else {
                            Toast.push('error', $translate.instant('toast.contents.reset.video.error'));
                        }
                        setTimeout(function() {
                        }, 500);
                    }
                };
                xhr.send(JSON.stringify(data));

                this.recording = 0;
            };

           this.startStatusPing = function() {
                console.log("Starting status ping");
                this.getStatus(this.url + "status");
                
                setInterval(this.getStatus, 10000, this.url + "status");
           }

	   this.getStatus = function(status_url) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", status_url, true);
                xhr.send();

                if (this.recording === 1 && xhr.responseText === 'stopped') {
                    var name = prompt($translate.instant('toast.contents.reset.video.prompt'));
                    this.stopRecording(name);
                    this.recording = 0;
                }
            };
        };

     	return new Recorder();
    };

    videoFactory().$inject = [];
    angular.module('recorders', []).factory('Recorder', videoFactory);
})();
