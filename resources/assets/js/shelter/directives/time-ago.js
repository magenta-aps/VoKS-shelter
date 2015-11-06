/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var timeAgoDirective = function($interval) {
        var countTime = function (started) {
            started = Math.floor(started / 1000);
            var count = Math.floor(Date.now() / 1000) - parseInt(started, 10),
                hours = Math.floor(count / 3600),
                minutes = Math.floor((count - (hours * 3600)) / 60),
                seconds = count - (hours * 3600) - (minutes * 60);

            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            return hours + ':' + minutes + ':' + seconds;
        };

        return {
            restrict: 'AE',
            link: function(scope, element, attrs) {
                var interval = null;

                scope.$watch(attrs.timeAgo, function(value){
                    $interval.cancel(interval);

                    interval = $interval(function(){
                        if(typeof value !== 'undefined') {
                            element[0].innerHTML = countTime(value);
                        }
                    }, 1000);

                    element[0].innerHTML = '00:00:00';
                });
            }
        };
    };

    timeAgoDirective.$inject = ['$interval'];
    angular.module('app').directive('timeAgo', timeAgoDirective);
})();