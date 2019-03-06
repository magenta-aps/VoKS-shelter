/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    // Find first filter
    var findFirstFilter = function() {
        return function(input) {
            if (!input || !input.length) {
                return null;
            }

            return input[0];
        };
    };

    findFirstFilter.$inject = [];
    angular.module('app').filter('findFirst', findFirstFilter);

    // Stream directive
    var streamDirective = function() {
        return {
            transclude: true,
            controller: 'StreamController',
            controllerAs: 'StreamCtrl',
            scope: {
                client: "=",
                clients: "="
            },
            link: function(scope, element, attrs, ctrl, transclude) {
                transclude(scope, function(clone) {
                    element.append(clone);
                });
                scope.position = parseInt(attrs.position, 10);
            }
        };
    };

    streamDirective.$inject = [];
    angular.module('app').directive('stream', streamDirective);

    // Video directive
    var videoDirective = function($rootScope, $route, $timeout, State, MapState) {
        var scale = function($video) {
            var parentNode = $video.parents('.streams__video-tag-block')[0],
                video = $video[0];

            if (!(video.videoWidth || video.videoHeight)) {
                return;
            }

            var videoWidth = video.videoWidth,
                videoHeight = video.videoHeight,
                parentWidth = parentNode.offsetWidth,
                parentHeight = parentNode.offsetHeight;

            if ((videoWidth <= 0) || (videoHeight <= 0) || (parentWidth <= 0) || (parentHeight <= 0)) {
                return true;
            }

            // scale to the target width
            var scaleX1 = parentWidth;
            var scaleY1 = (videoHeight * parentWidth) / videoWidth;

            // scale to the target height
            var scaleX2 = (videoWidth * parentHeight) / videoHeight;
            var scaleY2 = parentHeight;

            // now figure out which one we should use
            var fScaleOnWidth = (scaleX2 > parentWidth);
            fScaleOnWidth = (fScaleOnWidth) ? false : true;

            if (fScaleOnWidth) {
                scaleX2 = scaleX1;
                scaleY2 = scaleY1;
            }

            video.setAttribute('width', Math.floor(scaleX2));
            video.setAttribute('height', Math.floor(scaleY2));
            video.style.left = Math.floor((parentWidth - Math.floor(scaleX2)) / 2) + 'px';
            video.style.top = Math.floor((parentHeight - Math.floor(scaleY2)) / 2) + 'px';            
        };
        
        var setVideoStream = function($video, client) {
            if (client === null) {
                return;
            }
            
            if (client.stream.object.active !== true) {
                return;
            }
            
            var video = $video[0];
            if (video.srcObject) {
                if (video.srcObject.active === true) {
                    return;
                }
            }
            
            if (client.stream.object && video) {
                video.srcObject = client.stream.object;
            }
        };

        return {
            link: function($scope, $element, $attrs) {
                $timeout(function() {
                    scale($element);
                }, 200);
                
                $timeout(function() {
                    console.log('Video Directive: 1 (NO)');
                    console.log($scope.client);
                    console.log($element[0].srcObject);
                }, 1000);
                    
                $scope.$watch($attrs.active, function() {
                    scale($element);
                    $timeout(function() {
                        if (null !== $scope.client) {
                            setVideoStream($element, $scope.client); 
                        }
                        console.log('Video Directive: 2 (OK)');
                        console.log($scope.client);
                        console.log($element[0].srcObject);
                    }, 1500);
                });

                $rootScope.$watch('tab', function() {
                    $timeout(function() {
                        scale($element);
                    }, 200);
                    $timeout(function() {
                        console.log('Video Directive: 3 (NO)');
                        console.log($scope.client);
                        console.log($element[0].srcObject);
                    }, 1000);
                });

                $scope.$watch('client.state.chatOpen', function() {
                    $timeout(function() {
                        scale($element);
                    }, 200);
                    $timeout(function() {
                        scale($element);
                        console.log('Video Directive: 4 (NO)');
                        console.log($scope.client);
                        console.log($element[0].srcObject);
                    }, 1000);
                });
                
                $(window).resize(function() {
                    $timeout(function() {
                        scale($element);
                    }, 200);
                    $timeout(function() {
                        scale($element);
                        console.log('Video Directive: 5 (NO)');
                        console.log($scope.client);
                        console.log($element[0].srcObject);
                    }, 1000);
                });

                $element.on('loadedmetadata', function() {
                    scale($(this));
                    console.log('Video Directive: 6 (OK)');
                    console.log($scope.client);
                    console.log($element[0].srcObject);
                    if (null === State.selected.marker && null !== $scope.client) {
                        var client = $scope.client,
                            clientId = client.profile.id;
                        setVideoStream($(this), $scope.client);
                        if (client.position.floor && client.profile.mac_address) {
                            // Get active route and map state group
                            var route = $route.current.active,
                                state = ('plan' === route) ? 'map' : 'stream';
                            console.log(state);
                            MapState.state[state].floor = client.position.floor;
                            MapState.state[state].pan = client.profile.mac_address;
                            State.selected.marker = clientId;
                        }
                    }
                });
            }
        };
    };

    videoDirective.$inject = ['$rootScope', '$route', '$timeout', 'State', 'MapState'];
    angular.module('app').directive('video', videoDirective);

    // Listen directive
    var listenDirective = function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                scope.$watch(attrs.listen, function(muted) {
                    if (muted || typeof muted === 'undefined') {
                        element[0].muted = true;
                    } else {
                        element[0].muted = false;
                    }
                });
            }
        };
    };

    listenDirective.$inject = [];
    angular.module('app').directive('listen', listenDirective);

    // Active directive
    var activeDirective = function() {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                scope.$watch(attrs.active, function(active) {
                    if (!active) {
                        element[0].src = '';
                    }
                });
            }
        };
    };

    activeDirective.$inject = [];
    angular.module('app').directive('active', activeDirective);

    // Trusted filter
    angular.module('app').filter('trusted', ['$sce', function($sce) {
        return function(url) {
            return $sce.trustAsResourceUrl(url);
        };
    }]);
})();