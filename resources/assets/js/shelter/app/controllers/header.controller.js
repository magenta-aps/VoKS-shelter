/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var headerController = function($rootScope, $route, ShelterAPI, State, Connections, QuickHelpService) {
        var Header = this;

        /**
         * ------------------------------------------------------------------
         * Navigation
         * ------------------------------------------------------------------
         */

        /**
         * Checks if provided path is of active menu item
         */
        Header.isMenu = function(path) {
            if (undefined !== $route.current) {
                return $route.current.active === path;
            }
        };

        /**
         * Resets single stream client id on route change
         */
        $rootScope.$on('$routeChangeStart', function(event, next) {
            var clientId = State.singleStream;
            if (null !== clientId && undefined === next.params.clientId) {
                Connections.getClient(clientId).minimize();
            }
        });

        /**
         * ------------------------------------------------------------------
         * Status
         * ------------------------------------------------------------------
         */
        /**
         * Shelter status
         *
         * @type {Object}
         */
        Header.status = ShelterAPI.status;

        /**
         * Header texts
         *
         * @type {Object}
         */
        Header.text = {
            alarm: {
                0: 'header.status.alarm.off',
                1: 'header.status.alarm.on'
            },
            heading: {
                0: 'header.heading.no',
                1: 'header.heading.asked',
                2: 'header.heading.yes',
                3: 'header.heading.cancelled'
            }
        };

        /**
         * Call police.
         * Triggers the alarm if necessary.
         */
        Header.policeOn = function() {
            if ('off' === Header.status.police.called) {
                ShelterAPI.police(1);
            }
        };

        /**
         * Cancel police.
         */
        Header.policeOff = function() {
            if ('on' === Header.status.police.called) {
                ShelterAPI.police(0);
            }
        };

        /**
         * Checks if header status is on
         *
         * @returns {Boolean}
         */
        Header.isPoliceCalled = function() {
            return 'on' === Header.status.police.called;
        };

        /**
         * ------------------------------------------------------------------
         * Quick help
         * ------------------------------------------------------------------
         */
        Header.showQuickHelp = function() {
            QuickHelpService.init().then(function() {
                QuickHelpService.start();
                window.onbeforeunload = function() {
                    QuickHelpService.stop();
                };
            });
        };
    };

    headerController.$inject = [
        '$rootScope', '$route',
        'ShelterAPI', 'State', 'Connections', 'QuickHelpService'
    ];
    angular.module('app.controllers').controller('HeaderController', headerController);
})();