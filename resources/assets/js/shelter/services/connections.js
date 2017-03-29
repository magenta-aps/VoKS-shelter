/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var connectionsService = function($q, $timeout, $route, $location,
                                          ShelterAPI, SocketService, Messages,
                                          RTCConnection, State, MapState, Client) {
        /**
         * Microphone enabled or disabled
         * Enabled by defaut.
         *
         * Shouldn't belong to Connections
         *
         * @access private
         * @type {Boolean}
         */
        var _microphone = true;

        /**
         * Client connection pool
         *
         * @access private
         * @type {Array}
         */
        var _clients = [];

        /**
         * Timeout for coordinate pulling
         *
         * @type integer
         */
        var activeTimeout = 5000,
            inactiveTimeout = 10000;

        /**
         * -----------------------------------------------
         * Microphone
         * -----------------------------------------------
         */
        function enableMicrophone() {
            _microphone = true;
        }

        function disableMicrophone() {
            _microphone = false;
        }

        /**
         * -----------------------------------------------
         * Clients
         * -----------------------------------------------
         */

        /**
         * Get all available connections
         *
         * @return {Array} All connections
         */
        function getClients() {
            return _clients;
        }

        /**
         * Retrieve client
         * Finds and returns only the first occurrence!
         *
         * @param {String} key Client profile property
         * @param {Mixed} value Client profile property value
         *
         * @return {Object|null} Reference to client or null
         */
        function getClient(key, value) {
            if (undefined === value) {
                value = key;
                key = 'id';
            }

            var clients = getClients(),
                i = 0, l = clients.length;

            for (i; i < l; i++) {
                var client = clients[i];

                if (value === client.profile[key]) {
                    return client;
                }
            }

            return null;
        }

        /**
         * Add client to _clients array
         *
         * @param {Object} clientModel Client model
         */
        function addClient(clientModel) {
            _clients.push(clientModel);
        }

        /**
         * Set police called status
         *
         * @param {Array} callers List of callers
         */
        function setPoliceStatus(callers) {
            if (0 < callers.length) {
                var i = 0, l = callers.length;
                for (i; i < l; i++) {
                    var clientId = callers[i],
                        client = getClient(clientId);

                    if (null !== client && false === client.state.calledPolice) {
                        // Update client
                        client.updateActivity();
                        client.state.calledPolice = true;
                        client.save();

                        // Update messages
                        Messages.setPoliceStatus(clientId);
                    }
                }
            }
        }

        /**
         * -----------------------------------------------
         * Locations
         * -----------------------------------------------
         */
        // Gets all coordinates
        var inCall = false;
        var inTimeOut = false;

        var safeCall = function(){
            inTimeOut = false;
            getAllCoordinates();
        };

        var safeTimeOut = function(timeout){
            inCall = false;
            inTimeOut = true;
            $timeout(safeCall, timeout);
        };

        function getAllCoordinates() {
            if(!inCall && !inTimeOut) {

                var timeout = inactiveTimeout;

                if (undefined !== $route.current && 'plan' === $route.current.active) {
                    timeout = activeTimeout;
                }

                inCall = true;

                ShelterAPI.getCoordinates()
                    .then(
                        function(response) {
                            _updateCoordinates(response.data);
                            safeTimeOut(timeout);
                        },
                        function() {
                            safeTimeOut(timeout);
                        }
                    )
                ;

            } else {
                return;
            }
        }

        // Gets only seven active client coordinates
        var inCall7 = false;
        var inTimeOut7 = false;

        var safeCall7 = function(){
            inTimeOut7 = false;
            getSevenCoordinates();
        };

        var safeTimeOut7 = function(timeout){
            inCall7 = false;
            inTimeOut7 = true;
            $timeout(safeCall7, timeout);
        };

        function getSevenCoordinates() {
            if(!inCall7 && !inTimeOut7) {
                var timeout = inactiveTimeout;
                if (undefined !== $route.current && 'stream' === $route.current.active) {
                    timeout = activeTimeout;
                }

                // Filter active
                var macs = [],
                    list = _clients,
                    length = list.length;

                for (var index = 0; index < length; index++) {
                    var client = list[index];
                    if (0 < client.position.index) {
                        macs.push(client.profile.mac_address);
                    }
                }

                ShelterAPI.getCoordinates(macs)
                    .then(function (response) {
                        _updateCoordinates(response.data);
                        safeTimeOut7(timeout);
                    }, function () {
                        safeTimeOut7(timeout);
                    });
            } else {
                return;
            }
        }

        // Updates clients and their coordinates
        function _updateCoordinates(list) {
            var i = 0,
                l = list.length;

            for (i; i < l; i++) {
                var _client = list[i],
                    client = getClient('mac_address', _client.profile.mac_address);

                // Create new client
                if (null === client) {
                    client = new Client();

                    // Set profile
                    client.profile.name = _client.profile.name || 'Client';
                    client.profile.mac_address = _client.profile.mac_address;
                    client.profile.device = _client.profile.device || 'desktop';

                    addClient(client);
                }

                // GCM id
                if (undefined !== _client.profile.gcm_id) {
                    client.profile.gcmId = _client.profile.gcm_id;
                }

                // Set position
                client.position.floor = _client.position.floor_id;
                client.position.x = _client.position.x;
                client.position.y = _client.position.y;
            }
        }

        /**
         * -----------------------------------------------
         * RTC
         * -----------------------------------------------
         */

        /**
         *
         * @param client
         * @returns {RTCConnection}
         */
        function createRTCConnection(client) {
            var options = {
                onStream: function(stream) {
                    client.setStream(stream);

                    if (client.state.talking) {
                        client.getConnection().streams.local.resume();
                    }
                }
            };

            var connection = new RTCConnection(client.profile.id, options, _microphone);
            return connection;
        }

        /**
         * -----------------------------------------------
         * SocketService callbacks
         * -----------------------------------------------
         */

        /**
         * Process Reset Shelter message
         * WS: RESET
         */
        function wsResetShelter() {
            window.localStorage.clear();
            setTimeout(function() {
                window.localStorage.clear();
                window.location.reload();
            }, 100)
        }

        /**
         * Process Ping message
         * WS: PING
         * @private
         */
        function wsPing() {
            SocketService.sendRaw({
                type: 'PONG'
            });
        }

        /**
         * Process Client List Update message
         * WS: CLIENT_LIST_UPDATE
         *
         * @param {Object} message Socket Service message
         */
        function wsClientListUpdate(message) {
            var clientList = message.data;

            for (var clientId in clientList) {
                if (undefined !== clientList[clientId]) {
                    var _client = clientList[clientId],
                        client = getClient('mac_address', _client.profile.mac_address);

                    if (null === client) {
                        client = new Client(clientId);
                        addClient(client);
                    }

                    // Update client profile
                    client.profile.device = _client.profile.device;
                    client.profile.id = _client.profile.device_id;
                    client.profile.gcmId = _client.profile.gcm_id;
                    client.profile.mac_address = _client.profile.mac_address;
                    client.profile.name = _client.profile.name;
                    client.profile.type = 'client';

                    // Update client position
                    client.position.floor = _client.position.floor_id;
                    client.position.x = _client.position.x;
                    client.position.y = _client.position.y;

                    client.init();
                }
            }
        }

        /**
         * Process Client Connected message
         * WS: CLIENT_CONNECTED
         */
        function wsClientConnect(message) {
            var _client = message.profile,
                client = getClient('mac_address', _client.profile.mac_address);

            if (null === client) {
                client = new Client(_client.profile.device_id);
                addClient(client);
            }

            // Update client profile
            client.profile.device = _client.profile.device;
            client.profile.id = _client.profile.device_id;
            client.profile.gcmId = _client.profile.gcm_id;
            client.profile.mac_address = _client.profile.mac_address;
            client.profile.name = _client.profile.name;
            client.profile.type = 'client';

            // Update client position
            client.position.floor = _client.position.floor_id;
            client.position.x = _client.position.x;
            client.position.y = _client.position.y;

            client.init();

            // RTC
            var connection = client.getConnection();
            var recreateRTCConnection = function(connection, client) {
                if (client.position.inView) {
                    client.sendMessage('LISTENING', client.state.talking ? 1 : 0);
                }
            };

            if (connection) {
                try {
                    recreateRTCConnection(connection, client);
                } catch (e) {
                    // Exception
                }
            } else {
                client.setConnection(createRTCConnection(client));
                connection = client.getConnection();
                recreateRTCConnection(connection, client);
            }
        }

        /**
         * Process Client Disconnected message
         * WS: CLIENT_DISCONNECTED
         *
         * @param {Object} message Socket Service message
         */
        function wsClientDisconnect(message) {
            var clientId = message.src,
                client = getClient(clientId);

            // exit if client does not exist
            if (null === client) {
                return;
            }

            // Close single stream view
            if (client.position.inLargeView) {
                client.minimize();
                $location.path('/stream').replace();
            }

            client.destroy();
        }

	    /**
	     * Process Client Location updatemessage
	     * WS: LOCATION
	     */
	    function wsClientCoordinates(message)
	    {
		    var client = getClient( message.src );

		    if (null !== client)
		    {
		        try
                {
	                var coordinates = JSON.parse( message.data );
	                // client.position.floor = _client.position.floor_id;

	                console.log( 'LOCATION CALL', coordinates, coordinates['LAT'], coordinates['LON'] );

	                if ( angular.isDefined(coordinates['LAT']) && angular.isDefined(coordinates['LON']) )
                    {
	                    client.position.lat = coordinates['LAT'];
	                    client.position.lng = coordinates['LON'];
	                    console.log( 'LOCATION UPDATE', coordinates['LAT'], coordinates['LON'], client.position.lat, client.position.lng );
                    }
                }
		        catch ( e ) { /** silence is golden **/ }
		    }
	    }

	    /**
         * Process Offer, Answer & Candidate RTC messages
         * WS: OFFER, ANSWER, CANDIDATE
         *
         * REFACTOR
         *
         * @param {Object} message Socket Service message
         */
        function wsRtcMessages(message) {
            var clientId = message.src,
                client = getClient(clientId);

            if (null !== client) {
                var connection = client.getConnection();

                if (!connection) {
                    client.setConnection(createRTCConnection(client));
                }

                client.getConnection().process(message.type, message.payload);

                if ('OFFER' === message.type) {
                    if (!client.state.talking) {
                        if (!client.state.calling) {
                            client.sendMessage('CALL_STATE', 0);
                        }
                    } else if (client.state.talking) {
                        if (!client.position.inQueue) {
                            client.sendMessage('CALL_STATE', 1);
                        } else {
                            client.sendMessage('CALL_STATE', 0);
                        }
                    }
                }
            }
        }

        /**
         * Process Shelter Update message
         * WS: SHELTER_UPDATE
         *
         * REFACTOR
         *
         * @param {Object} message Socket Service message
         */
        function wsUpdateShelterStats(message) {
            var data = message.data;
            ShelterAPI.processStatus(data);
            setPoliceStatus(data.callers);
        }

        /**
         * Process Peer Reconnect message
         * WS: PEER_RECONNECT
         *
         * @param {Object} message Socket Service message
         */
        function wsPeerReconnect(message) {
            var client = getClient(message.src);
            if (null !== client) {
                client.closeRtcConnection();
            }
        }

        /**
         * Process Message message
         * WS: MESSAGE
         *
         * REFACTOR
         *
         * @param {Object} message Socket Service message
         */
        function wsChatMessage(message) {
            var client = getClient(message.src);

            var _message = Messages.createMessage({
                id: message.src,
                name: message.profile.name,
                isShelter: false,
                calledPolice: false,
                timestamp: message.timestamp,
                message: message.data
            });

            if (null !== client) {
                _message.calledPolice = client.state.calledPolice;

                client.messages.list.push(angular.copy(_message));
                if (!self.state.chatOpen && !self.state.focused) {
                    client.markUnread();
                }

                client.updateActivity();
                client.save();
            }

            Messages.pushMessage(angular.copy(_message));
        }

        /**
         * Process Request Call message
         * WS: REQUEST_CALL
         *
         * REFACTOR
         *
         * @param {Object} message Socket Service message
         */
        function wsCallRequest(message) {
            var client = getClient(message.src);

            //only allow calls if
            if (null !== client && client.state.canReceiveAudio) {
                client.state.calling = (message.data) ? true : false;
                if (client.state.calling === true) {
                    client.updateActivity();
                }

                client.state.talking = false;

                client.save();
            }
        }

        /**
         * Process Battery Level message
         * WS: BATTERY_LEVEL
         */
        function wsBatteryLevel(message) {
            var client = getClient(message.src);
            if (null !== client) {
                client.state.battery = message.data + '%';
                client.timestamps.lastActive = Date.now();
                client.save();
            }
        }

        /**
         * -----------------------------------------------
         * Setup SocketService
         * -----------------------------------------------
         */
        SocketService.onMessage('RESET', wsResetShelter);

        SocketService.onMessage('PING', wsPing);

        SocketService.onMessage('CLIENT_LIST_UPDATE', wsClientListUpdate);
        SocketService.onMessage('CLIENT_CONNECTED', wsClientConnect);
        SocketService.onMessage('CLIENT_DISCONNECTED', wsClientDisconnect);

        SocketService.onMessage('LOCATION', wsClientCoordinates);

        SocketService.onMessage('OFFER ANSWER CANDIDATE', wsRtcMessages);
        SocketService.onMessage('SHELTER_UPDATE', wsUpdateShelterStats);
        SocketService.onMessage('PEER_RECONNECT', wsPeerReconnect);
        SocketService.onMessage('MESSAGE', wsChatMessage);
        SocketService.onMessage('REQUEST_CALL', wsCallRequest);
        SocketService.onMessage('BATTERY_LEVEL', wsBatteryLevel);

        // Get Shelter status
        ShelterAPI.getStatus().then(function(response) {
            var data = response.data;
            ShelterAPI.processStatus(data);
            setPoliceStatus(data.callers);
        });

        // Start pulling coordinates
        getAllCoordinates();
        getSevenCoordinates();

        /**
         * Connections service
         *
         * @type {Object}
         *
         * @return {Object} Connections service
         */
        var service = {
            // microphone
            enableMicrophone: enableMicrophone,
            disableMicrophone: disableMicrophone,

            // clients
            getClients: getClients,
            getClient: getClient,

            addClient: addClient,

            createRTCConnection: createRTCConnection
        };
        return service;
    };

    connectionsService.$inject = [
        // Angular services
        '$q',
        '$timeout',
        '$route',
        '$location',

        // Services
        'ShelterAPI',
        'SocketService',
        'Messages',
        'RTCConnection',
        'State',
        'MapState',

        // Models
        'Client'
    ];
    angular.module('app').service('Connections', connectionsService);
})();
