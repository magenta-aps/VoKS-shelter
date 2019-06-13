/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var resetShelterDirective = function(AdminApi, Toast, $translate, Recorder) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                angular.element(element).on('click', function() {
                    if (confirm($translate.instant('toast.contents.reset.message'))) {
                        AdminApi.resetShelter(attrs.resetShelter).success(function() {
                            Toast.push('success', $translate.instant('toast.contents.reset.success'), '');
                            localStorage.clear();

                            if(config['video-do-recording']) {
                                var url = config['video-base-url'];
				$http.get(url + 'status')
				     .success( function (data) {
					if(data['Status'] === 1) {
					    var name = prompt($translate.instant('toast.contents.reset.video.prompt')); 
                            		    Recorder.stopRecording(name);
					}
				     }); 
                            }
			    
			    setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        });
                    }
                });
            }
        };
    };

    resetShelterDirective.$inject = ['AdminApi', 'Toast', '$translate', 'Recorder'];
    angular.module('admin').directive('resetShelter', resetShelterDirective);
})();
