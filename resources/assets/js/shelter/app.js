/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    /**
     * Angular app module
     * @type Array
     */
    angular.module('app', [
        'ngRoute',
        'pascalprecht.translate',

        'app.directives',
        'app.controllers',
        'app.services',
        'map',

        'toasts',
        'ui.select',
        'luegg.directives',
        'perfect_scrollbar',
        'socket'
    ]);

    /**
     * Various angular config
     *
     * Configure:
     * $provide
     * $interpolateProvider
     * $sceProvider
     */
    var ngConfig = function($provide, $interpolateProvider, $sceProvider) {
        $interpolateProvider.startSymbol('<%').endSymbol('%>');
        $sceProvider.enabled(false);

        // Decorate $q with a state property
        $provide.decorator('$q', ['$delegate', function($delegate) {
            var defer = $delegate.defer;
            $delegate.defer = function() {
                var deferred = defer();

                deferred.promise.state = deferred.state = 'pending';

                deferred.promise.then(function() {
                    deferred.promise.state = deferred.state = 'fulfilled';
                }, function() {
                    deferred.promise.state = deferred.state = 'rejected';
                });

                return deferred;
            };
            return $delegate;
        }]);
    };

    ngConfig.$inject = ['$provide', '$interpolateProvider', '$sceProvider'];
    angular.module('app').config(ngConfig);

    /**
     * Router configuration
     * $routeProvider
     */
    var angularRoute = function($routeProvider, $locationProvider) {
        $routeProvider
            .when('/stream', {
                active: 'stream',
                controller: 'StreamController',
                controllerAs: 'StreamCtrl',
                templateUrl: '/views/streams.html'
            })
            .when('/stream/:clientId', {
                active: 'stream',
                controller: 'StreamController',
                controllerAs: 'StreamCtrl',
                templateUrl: '/views/stream.html'
            })
            .when('/plan', {
                active: 'plan',
                templateUrl: '/views/map.html'
            })
            .when('/help', {
                active: 'help',
                controller: 'HelpController',
                templateUrl: '/views/help.html'
            })
            .when('/reset', {
                controller: 'ResetController',
                template: 'Resetting shelter...'
            })
            .otherwise({
                redirectTo: '/stream'
            });

        // Navigation with pushState
        if (window.history && window.history.pushState) {
            $locationProvider.html5Mode({
                enabled: false,
                requireBase: false
            });
        }
    };
    angularRoute.$inject = ['$routeProvider', '$locationProvider'];
    angular.module('app').config(angularRoute);

    /**
     * Translations
     * $translateProvider
     */
    var angularTranslate = function($translateProvider) {
        // Language and it's translations
        // Point "translations" to a proper translation JSON here
        var language = config.locale,
            translations = config.lang;

        // Escaping strategy
        $translateProvider.useSanitizeValueStrategy('escape');

        // Set translations
        $translateProvider
            .translations(language, translations)
            .preferredLanguage(language);
    };

    angularTranslate.$inject = ['$translateProvider'];
    angular.module('app').config(angularTranslate);

    /**
     * Maps config
     */
    var mapConfig = function(MapConfigProvider) {
        /**
         * Maps (id: page)
         * - #streams: Home page
         * - #stream: Single stream page
         * - #map: School plan
         *
         * State groups
         * - map (School plan)
         * - stream (Home & single stream pages)
         */
        MapConfigProvider.setConfig({
            control: false,
            leaflet: {
                attributionControl: false,
                boxZoom: false,
                center: [0, 0],
                crs: 'Simple',
                maxZoom: 10,
                minZoom: 1,
                zoom: 5,
                zoomControl: false,

                zoomInTitle: 'map.zoom.in',
                zoomOutTitle: 'map.zoom.out'
            }
        });

        // Override Home page options
        MapConfigProvider.setConfig('streams', {
            state: 'stream'
        });

        // Override Single stream options
        MapConfigProvider.setConfig('stream', {
            state: 'stream'
        });

        // Override School plan options
        MapConfigProvider.setConfig('map', {
            state: 'map',
            control: true
        });
    };

    var mapRun = function(MapState, MapData) {
        // Initialize state service, set state groups
        MapState.init(['map', 'stream']);

        // Load map data
        var url = '/api/shelter/maps/' + config['num-id'];
        var success = function(data) {
            // Preload floor images
            var length = data.floors.length;
            for (var index = 0; index < length; index++) {
                var _floor = data.floors[index],
                    _image = new Image();

                _image.src = _floor.image;
            }

            // Get & set active building and floor
            var buildings = MapData.getBuildings(),
                building = buildings[0],
                floors = MapData.getFloors(building.id),
                floor = floors[0];

            for (var state in MapState.state) {
                if (undefined !== MapState.state[state]) {
                    MapState.state[state].building = building.id;
                    MapState.state[state].floor = floor.id;
                }
            }
        };

        MapData.load(url).then(success);
    };
    mapConfig.$inject = ['MapConfigProvider'];
    mapRun.$inject = ['MapState', 'MapData'];
    angular.module('app').config(mapConfig).run(mapRun);

    /**
     * SocketService
     */
    var socketServiceProvider = function(SocketServiceProvider) {
        SocketServiceProvider.setConfig('url', config['websocket-server']);
    };
    socketServiceProvider.$inject = ['SocketServiceProvider'];
    angular.module('app').config(socketServiceProvider);

    /**
     * Everything else
     */
    angular
        .module('app')
        .config(['uiSelectConfig', function(uiSelectConfig) {
            uiSelectConfig.theme = 'select2';
        }])
        .run(['$rootScope', function($rootScope) {
            $rootScope.safeApply = function(fn) {
                var phase = $rootScope.$$phase;
                if (phase === '$apply' || phase === '$digest') {
                    if (fn && (typeof(fn) === 'function')) {
                        fn();
                    }
                } else {
                    this.$apply(fn);
                }
            };
            $rootScope.config = config;
        }])
        .filter('html', ['$sce', function($sce) {
            return function(text) {
                return $sce.trustAsHtml(text);
            };
        }])
        .directive('replace', function() {
            return {
                require: 'ngInclude',
                restrict: 'A',
                link: function($scope, el) {
                    el.replaceWith(el.children());
                }
            };
        });
})();