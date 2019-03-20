/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var resetShelterDirective = function(AdminApi, Toast, $translate) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                angular.element(element).on('click', function() {
                    if (confirm($translate.instant('toast.contents.reset.message'))) {
                        AdminApi.resetShelter(attrs.resetShelter).success(function() {
                            Toast.push('success', $translate.instant('toast.contents.reset.success'), '');
                            localStorage.clear();

                            if(config['video-do-recording']) {
                                var name = prompt($translate.instant('toast.contents.reset.video.prompt'));
                                if (name) {
                                    var url = "http://localhost:8080/stop";
                                    //var url = config['video-base-url'] + config['video-endpoint-stop']; // this for some reason becomes "undefinedhttp:/localhost8080stop" - must be something to do with string escaping
                                    var data = {name: name};
                                    var xhr = new XMLHttpRequest();
                                    xhr.open("POST", url);
                                    xhr.setRequestHeader("Content-Type", "application/json");
                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === 4) {
                                            if (xhr.status === 200) {
                                                Toast.push('success', $translate.instant('toast.contents.reset.video.success'));
                                            } else {
                                                Toast.push('error', $translate.instant('toast.contents.reset.video.error'));
                                            }
                                            setTimeout(function() {
                                                //window.location.reload();
                                            }, 500);
                                        }
                                    };
                                    xhr.send(JSON.stringify(data));
                                    // } else { send empty name and don't show video.success message? }
                                }
                            } else {
                                setTimeout(function() {
                                    window.location.reload();
                                }, 500);
                            }
                        });
                    }
                });
            }
        };
    };

    resetShelterDirective.$inject = ['AdminApi', 'Toast', '$translate'];
    angular.module('admin').directive('resetShelter', resetShelterDirective);
})();