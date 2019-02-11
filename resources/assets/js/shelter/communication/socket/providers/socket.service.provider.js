/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var socketServiceProvider = function() {
        /**
         * Provider API
         * @var {object}
         */
        var config = {
            url: 'ws://localhost',
            delay: 1000,
            minTimeout: null,
            maxTimeout: null
        };

        this.setConfig = function(key, val) {
            var type = typeof key;
            if ('string' !== type) {
                throw new TypeError('Invalid config key variable type: "' + type + '".');
            }
            config[key] = val;
        };

        this.$get = function($websocket, $timeout) {
            /**
             * Service API
             * @type {object}
             */

            function SocketService() {
                var ws = null,
                    stack = [],
                    self = this;

                // timeout
                if (null !== config.minTimeout) {
                    options.initialTimeout = config.minTimeout;
                }

                if (null !== config.maxTimeout) {
                    options.maxTimeout = config.maxTimeout;
                }

                this.connect = function() {
                    ws = $websocket(config.url);

                    for (var i = 0; i < stack.length; i++) {
                        self.on(stack[i].name, stack[i].options, stack[i].callback);
                    }
                };

                // listener
                this.on = function(name, options, callback) {
                    if (null === ws) {
                        stack.push({
                            name: name,
                            options: options,
                            callback: callback
                        });

                        return;
                    }

                    if (undefined === callback && 'function' === typeof options) {
                        callback = options;
                        options = {};
                    }

                    switch (name) {
                        case 'open':
                            ws.onOpen(function(event) {
                                callback.call(event, event);
                            });
                            break;
                        case 'close':
                            ws.onClose(function(event) {
                                callback.call(event, event);
                            });
                            break;
                        case 'error':
                            ws.onError(function(event) {
                                callback.call(event, event);
                            });
                            break;
                        case 'message':
                            ws.onMessage(function(event) {
                                callback.call(event, JSON.parse(event.data));
                            }, options);
                            break;
                        default:
                            break;
                    }
                };

                /**
                 *
                 * @param type
                 * @param callback
                 */
                this.onMessage = function(type, callback) {
                    this.on('message', function(messageObject) {
                        if (messageObject && 'undefined' !== typeof messageObject.type) {

                            //support multiple messages with the same callback
                            //defined by a space separator

                            var messages = type.split(' ');
                            for (var i = 0; i < messages.length; i++) {
                                if (messageObject.type === messages[i]) {
                                    callback(messageObject);
                                }
                            }
                        }
                    });
                };

                /**
                 * @param {string} type Message type
                 * @param {string} dst Destination device id
                 * @param {type} data Additional data
                 * @param {type} timestamp Unix timestamp
                 * @returns {Promise}
                 */
                this.send = function(type, dst, src, data, timestamp) {
                    return ws.send({
                        type: type,
                        dst: dst || null,
                        src: src,
                        data: data,
                        timestamp: timestamp || Date.now()
                    });
                };

                this.sendRaw = function(object) {
                    return ws.send(object);
                };

                /**
                 * @param {string} dst Destination device id
                 * @returns {Promise}
                 */
                this.openRtc = function(dst, src) {
                    return this.send('PEER_CONNECTION', dst, src, 1);
                };

                /**
                 * @param {string} dst Destination device id
                 * @returns {Promise}
                 */
                this.closeRtc = function(dst, src) {
                    return this.send('PEER_CONNECTION', dst, src, 0);
                };

                var reconnect = function() {
                    $timeout(function() {
                        self.connect();
                    }, config.delay);
                };

                // auto-reconnect
                this.on('close', reconnect);
            }

            return new SocketService();
        };
        this.$get.$inject = ['$websocket', '$timeout'];
    };

    socketServiceProvider.$inject = [];
    angular.module('socket.providers').provider('SocketService', socketServiceProvider);
})();