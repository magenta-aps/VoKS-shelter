
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */
/**
 * Socket module
 *
 * Requires ngWebSocket (angular-websocket)
 * @link https://github.com/gdi2290/angular-websocket
 */
(function() {
    'use strict';

    angular.module('socket', ['socket.providers', 'angular-websocket']);
})();