
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    function headerController($translate, Toast, AdminApi) {
        var Header = this;

        /**
         * ------------------------------------------------------------------
         * Status
         * ------------------------------------------------------------------
         */

        /**
         * Pull Shelter statistics
         */
        AdminApi.getStatus();

        /**
         * Shelter status
         *
         * @type {Object}
         */
        Header.status = AdminApi.status;

        /**
         * Header texts
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
                AdminApi.police(1);
            }
        };

        /**
         * Cancel police.
         */
        Header.policeOff = function() {
            if ('on' === Header.status.police.called) {
                AdminApi.police(0);
            }
        };

        /**
         * Checks if header status is on
         *
         * @returns {Boolean}
         */
        Header.isPoliceCalled = function () {
            return 'on' === Header.status.police.called;
        };

        /**
         * ------------------------------------------------------------------
         * Quick help
         * ------------------------------------------------------------------
         */
        Header.showQuickHelp = function() {
            Toast.push('warning',
                $translate.instant('toast.title.warning'),
                $translate.instant('toast.contents.help.not_available'));
        };
    }

    headerController.$inject = ['$translate', 'Toast', 'AdminApi'];
    angular.module('admin').controller('HeaderController', headerController);
})();