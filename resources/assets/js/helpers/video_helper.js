
(function() {
    'use strict';

    var videoFactory = function($http, Toast, $translate) {
        var Recorder = function() {
 
	    this.url = config['video-base-url'];
	    console.log(this.url);

            this.startRecording = function() {
                var start_url = this.url + "start";
            	$http.get(start_url);
	    };


	     this.stopRecording = function(name) {
                var stop_url = this.url + "stop";
                var data = {name: name || ""};

		$http.post(stop_url, JSON.stringify(data))
		.success( function () {
		    Toast.push('success', $translate.instant('toast.contents.reset.video.success'));
		})
		.error( function () {
                    Toast.push('error', $translate.instant('toast.contents.reset.video.error'));
		});
            };


           this.startStatusPing = function() {
                this.getStatus(this.url);
                
                setInterval(this.getStatus, 10000, this.url);
           };


	   this.getStatus = function(url) {
                $http.get(url + "status")
                     .success( function (data) {
			console.log(data);
		     	if ( data['Status'] === 2) {
                    	    var name = prompt($translate.instant('toast.contents.reset.video.prompt'));
			    var data = {name: name || ""};
	                    $http.post(url + "stop", JSON.stringify(data))
                	    .success( function () {
                    		Toast.push('success', $translate.instant('toast.contents.reset.video.success'));
                	    })
                	    .error( function () {
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
