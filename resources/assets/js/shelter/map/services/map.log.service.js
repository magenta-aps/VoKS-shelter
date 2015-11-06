
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mapLogService = function($log) {
        /**
         * Module name
         *
         * @private
         *
         * @type {String}
         */
        var _module = '[map.js]';

        /**
         * Log error
         *
         * @param {Number} code Status code
         * @param {String} text Status text
         * @param {String} url Optional URL
         */
        var error = function(code, text, url) {
            url = url || null;

            var args = [code, text],
                log = ['Error %d:', '%s'];

            if (null !== url) {
                args.push(url);
                log.push('URL: "%s"');
            }

            $log.error(_log(log, args));
        };

        /**
         * Create log message from arguments
         *
         * @private
         *
         * @returns {String}
         */
        var _log = function(log, args) {
            args.unshift(_module);
            var str =  args.join(' ');
            return vsprintf(str, args);
        };

        /**
         * Map log service
         * Public API
         */
        var service = {
            error: error,
        };
        return service;
    };

    mapLogService.$inject = ['$log'];
    angular.module('map.services').service('MapLog', mapLogService);
})();